<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        $payload = $request->all();

        // Verify signature
        $serverKey    = config('services.midtrans.server_key');
        $orderId      = $payload['order_id'] ?? '';
        $statusCode   = $payload['status_code'] ?? '';
        $grossAmount  = $payload['gross_amount'] ?? '';
        $signatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== ($payload['signature_key'] ?? '')) {
            Log::warning('Midtrans webhook: invalid signature for ' . $orderId);
            return response('Invalid signature', 403);
        }

        $payment = Payment::where('payment_number', $orderId)->with('order.items.product')->first();

        if (!$payment) {
            Log::warning('Midtrans webhook: payment not found for ' . $orderId);
            return response('Payment not found', 404);
        }

        $payment->update([
            'midtrans_transaction_id' => $payload['transaction_id'] ?? null,
            'midtrans_response'       => $payload,
        ]);

        $transactionStatus = $payload['transaction_status'] ?? '';
        $fraudStatus       = $payload['fraud_status'] ?? 'accept';
        $order             = $payment->order;

        match (true) {
            ($transactionStatus === 'capture' && $fraudStatus === 'accept'),
            ($transactionStatus === 'settlement')
                => $this->handleSuccess($payment, $order),

            in_array($transactionStatus, ['cancel', 'deny', 'expire'])
                => $this->handleCancel($payment, $order, $transactionStatus),

            $transactionStatus === 'refund'
                => $this->handleRefund($payment, $order),

            default => null,
        };

        return response('OK', 200);
    }

    private function handleSuccess(Payment $payment, Order $order): void
    {
        $payment->update(['status' => 'success', 'paid_at' => now()]);
        $order->update(['status' => 'processing', 'paid_at' => now()]);
    }

    private function handleCancel(Payment $payment, Order $order, string $transactionStatus): void
    {
        if (in_array($order->status, ['cancelled', 'refunded'])) {
            return;
        }

        DB::transaction(function () use ($payment, $order, $transactionStatus) {
            $payment->update([
                'status' => $transactionStatus === 'expire' ? 'expired' : 'failed',
            ]);

            $order->update([
                'status'       => 'cancelled',
                'cancelled_at' => now(),
            ]);

            // Restore stok
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }
        });
    }

    private function handleRefund(Payment $payment, Order $order): void
    {
        if ($order->status === 'refunded') {
            return;
        }

        DB::transaction(function () use ($payment, $order) {
            $payment->update(['status' => 'refunded']);

            $order->update(['status' => 'refunded']);

            // Restore stok jika order belum dikirim
            if (!in_array($order->status, ['shipped', 'completed'])) {
                foreach ($order->items as $item) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
        });
    }
}
