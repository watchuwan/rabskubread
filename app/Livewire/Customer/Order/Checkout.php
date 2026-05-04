<?php

namespace App\Livewire\Customer\Order;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Voucher;
use App\Services\PromotionService;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts.customer')]
class Checkout extends Component
{
    public $cartItems = [];
    public $addresses = [];
    public $shippingMethods = [];
    
    #[Rule('required|exists:addresses,id')]
    public ?int $selectedAddress = null;
    
    #[Rule('required|exists:shipping_methods,id')]
    public ?int $selectedShippingMethod = null;
    
    public string $voucherCode = '';
    public ?int $voucherId = null;
    public float $discount = 0;
    public float $promotionDiscount = 0;
    
    public float $subtotal = 0;
    public float $shippingCost = 0;
    public float $total = 0;
    
    public bool $agreeTerms = false;

    public function mount(): void
    {
        $this->loadCheckoutData();
    }

    public function loadCheckoutData(): void
    {
        $customer = auth()->guard('customer')->user();
        
        $cart = Cart::with('items.product.category')->where('customer_id', $customer->id)->first();
        
        if (!$cart || $cart->items->count() === 0) {
            redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong');
            return;
        }
        
        $this->cartItems = $cart->items->load('product');
        
        $this->addresses = Address::where('customer_id', $customer->id)
            ->orderBy('is_default', 'desc')
            ->get();
        
        if ($this->addresses->count() > 0) {
            $defaultAddress = $this->addresses->firstWhere('is_default', true);
            $this->selectedAddress = $defaultAddress?->id ?? $this->addresses->first()->id;
        }
        
        // Load shipping methods yang tersedia untuk semua produk di cart
        $productIds = $this->cartItems->pluck('product_id')->toArray();
        
        $this->shippingMethods = \App\Models\ShippingMethod::where('is_active', true)
            ->whereHas('products', function ($query) use ($productIds) {
                $query->whereIn('products.id', $productIds);
            })
            ->orderBy('sort_order', 'asc')
            ->get()
            ->filter(function ($method) use ($productIds) {
                // Hanya tampilkan shipping method yang support SEMUA produk di cart
                return $method->products()->whereIn('products.id', $productIds)->count() === count($productIds);
            })
            ->values();
        
        // Jika tidak ada shipping method, fallback ke PICKUP
        if ($this->shippingMethods->count() === 0) {
            $pickupMethod = \App\Models\ShippingMethod::where('code', 'PICKUP')
                ->where('is_active', true)
                ->first();
            
            if ($pickupMethod) {
                $this->shippingMethods = collect([$pickupMethod]);
            }
        }
        
        // Set default shipping method
        if ($this->shippingMethods->count() > 0 && !$this->selectedShippingMethod) {
            $this->selectedShippingMethod = $this->shippingMethods->first()->id;
            $this->shippingCost = $this->shippingMethods->first()->cost;
        }
        
        $this->calculateTotals();
    }

    public function calculateTotals(): void
    {
        $result = (new PromotionService())->applyToCartItems($this->cartItems);
        $this->cartItems         = $result['items'];
        $this->promotionDiscount = $result['promotion_discount'];

        $this->subtotal = $this->cartItems->sum(fn ($item) => $item->product->price * $item->quantity);
        $this->total    = $this->subtotal + $this->shippingCost - $this->promotionDiscount - $this->discount;
    }

    public function updatedSelectedShippingMethod($value): void
    {
        $shippingMethod = \App\Models\ShippingMethod::find($value);
        if ($shippingMethod) {
            $this->shippingCost = $shippingMethod->cost;
            $this->calculateTotals();
        }
    }

    public function applyVoucher(): void
    {
        if (empty(trim($this->voucherCode))) {
            $this->dispatch('toast', message: 'Masukkan kode voucher', type: 'error');
            return;
        }

        $voucher = Voucher::where('code', strtoupper(trim($this->voucherCode)))->first();

        if (!$voucher || !$voucher->isValid()) {
            $this->dispatch('toast', message: 'Kode voucher tidak valid atau sudah kadaluarsa', type: 'error');
            return;
        }

        $discountAmount = $voucher->calculateDiscount($this->subtotal);

        if ($discountAmount <= 0) {
            $this->dispatch('toast', message: 'Subtotal tidak memenuhi minimum order voucher ini (min. Rp ' . number_format($voucher->min_order_amount, 0, ',', '.') . ')', type: 'error');
            return;
        }

        $this->voucherId = $voucher->id;
        $this->discount  = $discountAmount;
        $this->calculateTotals();

        $this->dispatch('toast', message: 'Voucher berhasil diterapkan! Diskon Rp ' . number_format($discountAmount, 0, ',', '.'), type: 'success');
    }

    public function placeOrder(): void
    {
        try {
            $this->validate([
                'selectedAddress' => 'required|exists:addresses,id',
                'selectedShippingMethod' => 'required|exists:shipping_methods,id',
                'agreeTerms' => 'accepted',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('toast', message: 'Mohon lengkapi semua data yang diperlukan', type: 'error');
            throw $e;
        }

        // Recalculate to ensure promotion_price is set on current request
        $this->calculateTotals();

        $customer = auth()->guard('customer')->user();

        foreach ($this->cartItems as $item) {
            if (!$item->product->in_stock || $item->product->stock < $item->quantity) {
                $this->dispatch('toast', message: "Stok {$item->product->name} tidak mencukupi", type: 'error');
                return;
            }
        }

        $order = Order::create([
            'customer_id'        => $customer->id,
            'order_number'       => 'ORD-' . strtoupper(uniqid()),
            'status'             => 'pending',
            'subtotal'           => $this->subtotal,
            'shipping_cost'      => $this->shippingCost,
            'voucher_id'         => $this->voucherId,
            'voucher_discount'   => $this->discount + $this->promotionDiscount,
            'total_amount'       => $this->total,
            'shipping_method_id' => $this->selectedShippingMethod,
            'address_id'         => $this->selectedAddress,
            'notes'              => null,
        ]);

        if ($this->voucherId) {
            Voucher::find($this->voucherId)?->incrementUsage();
        }

        foreach ($this->cartItems as $item) {
            $price = $item->promotion_price ?? $item->product->price;
            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $item->product->id,
                'promotion_id' => $item->promotion_id ?? null,
                'quantity'     => $item->quantity,
                'price'        => $price,
                'subtotal'     => $price * $item->quantity,
            ]);
            $item->product->decrementStock($item->quantity);
        }

        $payment = Payment::create([
            'order_id'          => $order->id,
            'amount'            => $this->total,
            'status'            => 'pending',
        ]);

        // Generate Midtrans Snap Token
        try {
            \Midtrans\Config::$serverKey    = config('services.midtrans.server_key');
            \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
            \Midtrans\Config::$isSanitized  = true;
            \Midtrans\Config::$is3ds        = true;

            $address = \App\Models\Address::find($this->selectedAddress);

            $params = [
                'transaction_details' => [
                    'order_id'     => $payment->payment_number,
                    'gross_amount' => (int) $this->total,
                ],
                'customer_details' => [
                    'first_name' => $customer->name,
                    'email'      => $customer->email,
                    'phone'      => $customer->phone ?? '',
                    'billing_address' => [
                        'address'   => $address?->address ?? '',
                        'city'      => $address?->city ?? '',
                        'postal_code' => $address?->postal_code ?? '',
                    ],
                ],
                'item_details' => $this->cartItems->map(fn ($item) => [
                    'id'       => (string) $item->product->id,
                    'price'    => (int) ($item->promotion_price ?? $item->product->price),
                    'quantity' => $item->quantity,
                    'name'     => substr($item->product->name, 0, 50),
                ])->concat([
                    ['id' => 'SHIPPING', 'price' => (int) $this->shippingCost, 'quantity' => 1, 'name' => 'Ongkos Kirim'],
                ])->when($this->discount > 0, fn ($c) => $c->concat([
                    ['id' => 'DISCOUNT', 'price' => -(int) $this->discount, 'quantity' => 1, 'name' => 'Diskon'],
                ]))->values()->toArray(),
                'callbacks' => [
                    'finish'   => route('orders.payment', $order->id),
                    'unfinish' => route('orders.payment', $order->id),
                    'error'    => route('orders.payment', $order->id),
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            $payment->update([
                'snap_token'               => $snapToken,
                'midtrans_transaction_id'  => $payment->payment_number,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Midtrans Snap token error: ' . $e->getMessage());
        }

        $cart = Cart::where('customer_id', $customer->id)->first();
        if ($cart) {
            $cart->items()->delete();
        }

        $this->dispatch('cart-updated');

        $this->redirect(route('orders.payment', $order->id), navigate: true);
    }

    public function getPaymentMethodLogo($method)
    {
        // Priority 1: Check if method has logo in database (stored path or URL)
        if ($method->icon) {
            // If it's already a full URL, return as is
            if (str_starts_with($method->icon, 'http')) {
                return $method->icon;
            }
            
            // If it's a relative path, use Storage URL
            $path = $method->icon;
            
            // Check if file actually exists
            if (Storage::disk('public')->exists($path)) {
                return Storage::url($path);
            }
        }
        
        // Priority 2: Return placeholder based on payment type
        $code = strtolower($method->code ?? '');
        $name = strtolower($method->name ?? '');
        
        // Bank Transfer
        if (str_contains($code, 'bank') || str_contains($code, 'transfer') ||
            str_contains($name, 'bca') || str_contains($name, 'mandiri') ||
            str_contains($name, 'bni') || str_contains($name, 'bri')) {
            return '🏦';
        }
        
        // Credit/Debit Cards
        if (str_contains($code, 'card') || str_contains($code, 'visa') ||
            str_contains($code, 'mastercard')) {
            return '💳';
        }
        
        // E-Wallets
        if (str_contains($code, 'gopay')) return '🟢';
        if (str_contains($code, 'ovo')) return '🟣';
        if (str_contains($code, 'dana')) return '🔵';
        if (str_contains($code, 'shopee')) return '🟠';
        
        // Cash/COD
        if (str_contains($code, 'cod') || str_contains($code, 'cash')) {
            return '💰';
        }
        
        // Default: Bank icon
        return '🏦';
    }

    public function isPaymentMethodImage($method): bool
    {
        // Check if icon field contains an image path/URL
        if (!$method->icon) {
            return false;
        }
        
        // Check if it's a file path or URL (not an emoji)
        return str_contains($method->icon, '/') || 
               str_ends_with($method->icon, '.png') ||
               str_ends_with($method->icon, '.jpg') ||
               str_ends_with($method->icon, '.svg') ||
               str_ends_with($method->icon, '.webp');
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.customer.order.checkout');
    }
}
