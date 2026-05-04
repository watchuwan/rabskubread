@props(['status', 'size' => 'md'])

@php
    $colors = [
        'pending'    => 'bg-warning text-white',
        'processing' => 'bg-info text-white',
        'shipped'    => 'bg-neutral-800 text-white',
        'completed'  => 'bg-success text-white',
        'cancelled'  => 'bg-danger text-white',
    ];
    $labels = [
        'pending'    => 'Menunggu Pembayaran',
        'processing' => 'Diproses',
        'shipped'    => 'Dikirim',
        'completed'  => 'Selesai',
        'cancelled'  => 'Dibatalkan',
    ];
    $sizeClass = $size === 'sm' ? 'px-3 py-1 text-xs' : 'px-4 py-2 text-sm';
@endphp

<span class="rounded-full font-semibold {{ $sizeClass }} {{ $colors[$status] ?? 'bg-neutral-500 text-white' }}">
    {{ $labels[$status] ?? $status }}
</span>
