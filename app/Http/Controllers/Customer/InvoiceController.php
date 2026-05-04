<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function download(Order $order)
    {
        $customer = auth()->guard('customer')->user();

        if ($order->customer_id !== $customer->id) {
            abort(403);
        }

        $order->load(['items.product', 'address', 'shippingMethod', 'payment', 'customer']);

        $appName = \App\Models\Setting::get('app_name', config('app.name'));

        $logoSetting = \App\Models\Setting::where('key', 'app_logo')->first();
        $logo = $logoSetting?->getFirstMediaUrl('setting_image') ?: null;

        $pdf = Pdf::loadView('pdf.invoice', compact('order', 'appName', 'logo'));

        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }
}
