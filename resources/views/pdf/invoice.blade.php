<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 13px; color: #333; padding: 30px; }
        .header { display: flex; justify-content: space-between; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 15px; }
        .company-name { font-size: 22px; font-weight: bold; }
        .invoice-title { font-size: 18px; font-weight: bold; text-align: right; }
        .invoice-meta { text-align: right; margin-top: 5px; font-size: 12px; color: #555; }
        .section { margin-bottom: 20px; }
        .section-title { font-weight: bold; font-size: 13px; border-bottom: 1px solid #ccc; padding-bottom: 4px; margin-bottom: 8px; }
        .info-grid { display: flex; gap: 40px; }
        .info-block { flex: 1; }
        .info-row { margin-bottom: 4px; font-size: 12px; }
        .info-label { color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th { background: #f0f0f0; padding: 8px; text-align: left; font-size: 12px; border: 1px solid #ddd; }
        td { padding: 8px; border: 1px solid #ddd; font-size: 12px; }
        .text-right { text-align: right; }
        .totals { width: 280px; margin-left: auto; }
        .totals td { border: none; padding: 4px 8px; }
        .totals .total-row td { font-weight: bold; font-size: 14px; border-top: 2px solid #333; padding-top: 8px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 3px; font-size: 11px; font-weight: bold; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .footer { margin-top: 40px; text-align: center; font-size: 11px; color: #888; border-top: 1px solid #eee; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <div style="display:flex; align-items:center; gap:10px;">
            @if($logo)
                <img src="{{ $logo }}" style="height:50px; width:auto;">
            @endif
            <div>
                <div class="company-name">{{ $appName }}</div>
                <div style="font-size:12px; color:#555; margin-top:4px;">{{ \App\Models\Setting::get('app_tagline') }}</div>
            </div>
        </div>
        <div>
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-meta">#{{ $order->order_number }}</div>
            <div class="invoice-meta">{{ $order->created_at->format('d M Y') }}</div>
            <div style="margin-top:6px;">
                <span class="badge {{ $order->payment?->status === 'success' ? 'badge-success' : 'badge-warning' }}">
                    {{ strtoupper($order->payment?->status ?? 'pending') }}
                </span>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="info-grid">
            <div class="info-block">
                <div class="section-title">Kepada</div>
                <div class="info-row"><strong>{{ $order->customer->name }}</strong></div>
                <div class="info-row">{{ $order->customer->email }}</div>
                @if($order->customer->phone)
                    <div class="info-row">{{ $order->customer->phone }}</div>
                @endif
            </div>
            @if($order->address)
            <div class="info-block">
                <div class="section-title">Alamat Pengiriman</div>
                <div class="info-row">{{ $order->address->address }}</div>
                <div class="info-row">{{ $order->address->city }}, {{ $order->address->province }}</div>
                <div class="info-row">{{ $order->address->postal_code }}</div>
            </div>
            @endif
            <div class="info-block">
                <div class="section-title">Detail Pesanan</div>
                <div class="info-row"><span class="info-label">Tanggal:</span> {{ $order->created_at->format('d M Y, H:i') }}</div>
                <div class="info-row"><span class="info-label">Pengiriman:</span> {{ $order->shippingMethod?->name ?? '-' }}</div>
                @if($order->paid_at)
                    <div class="info-row"><span class="info-label">Dibayar:</span> {{ $order->paid_at->format('d M Y, H:i') }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Produk</div>
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals">
            <tr>
                <td>Subtotal</td>
                <td class="text-right">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Ongkos Kirim</td>
                <td class="text-right">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
            </tr>
            @if($order->voucher_discount > 0)
            <tr>
                <td>Diskon</td>
                <td class="text-right" style="color:#059669;">- Rp {{ number_format($order->voucher_discount, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td>Total</td>
                <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Terima kasih telah berbelanja di {{ $appName }}. Invoice ini dibuat secara otomatis.
    </div>
</body>
</html>
