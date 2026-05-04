@php
    $primary     = $this->theme_primary_color  ?: '#F59E0B';
    $hover       = $this->theme_primary_hover   ?: '#D97706';
    $accent      = $this->theme_accent_color    ?: '#FFE8A3';
    $bg          = $this->theme_bg_color        ?: '#FFFEF9';
    $text        = $this->theme_text_color      ?: '#1C1917';
    $navbarBg    = $this->theme_navbar_bg       ?: '#FFFFFF';
    $footerBg    = $this->theme_footer_bg       ?: '#1C1917';
    $cardBg      = $this->theme_card_bg         ?: '#FFFFFF';
    $border      = $this->theme_border_color    ?: '#E7E5E4';
    $btnText     = $this->theme_btn_text_color  ?: '#FFFFFF';
    $heroFrom    = $this->theme_hero_from       ?: '#FEF3C7';
    $heroTo      = $this->theme_hero_to         ?: '#FFFEF9';
    $fontSans    = $this->theme_font_sans       ?: 'Inter';
    $fontDisplay = $this->theme_font_display    ?: 'Playfair Display';
    $radiusMap   = ['rounded-none'=>'0px','rounded'=>'4px','rounded-lg'=>'8px','rounded-xl'=>'12px','rounded-2xl'=>'16px','rounded-3xl'=>'24px'];
    $radius      = $radiusMap[$this->theme_border_radius ?? 'rounded'] ?? '4px';
    $shadowMap   = ['shadow-none'=>'none','shadow-sm'=>'0 1px 2px rgba(0,0,0,.06)','shadow'=>'0 1px 3px rgba(0,0,0,.1)','shadow-md'=>'0 4px 6px rgba(0,0,0,.1)','shadow-lg'=>'0 10px 15px rgba(0,0,0,.1)'];
    $shadow      = $shadowMap[$this->theme_card_shadow ?? 'shadow-sm'] ?? 'none';
    $gf = urlencode($fontSans).':wght@400;600&family='.urlencode($fontDisplay).':wght@700';
@endphp

<link href="https://fonts.googleapis.com/css2?family={{ $gf }}&display=swap" rel="stylesheet">

<div class="rounded-xl border border-gray-200 overflow-hidden mt-2">
    {{-- Browser chrome --}}
    <div class="bg-gray-100 px-4 py-2 text-xs text-gray-500 font-medium flex items-center gap-2">
        <span class="w-3 h-3 rounded-full bg-red-400"></span>
        <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
        <span class="w-3 h-3 rounded-full bg-green-400"></span>
        <span class="ml-2">Preview Tampilan Toko</span>
    </div>

    <div style="background:{{ $bg }};font-family:'{{ $fontSans }}',sans-serif;color:{{ $text }};">

        {{-- Navbar --}}
        <div style="background:{{ $navbarBg }};border-bottom:1px solid {{ $border }};padding:10px 20px;display:flex;align-items:center;justify-content:space-between;">
            <span style="font-family:'{{ $fontDisplay }}',serif;font-weight:700;font-size:17px;color:{{ $text }};">Rabskubread</span>
            <div style="display:flex;gap:12px;align-items:center;">
                <span style="font-size:12px;color:{{ $text }}80;">Produk</span>
                <span style="font-size:12px;color:{{ $text }}80;">Tentang</span>
                <span style="background:{{ $primary }};color:{{ $btnText }};font-size:11px;font-weight:600;padding:5px 12px;border-radius:{{ $radius }};">Keranjang</span>
            </div>
        </div>

        {{-- Hero --}}
        <div style="background:linear-gradient(135deg,{{ $heroFrom }},{{ $heroTo }});padding:24px 20px;text-align:center;">
            <p style="font-size:10px;font-weight:700;color:{{ $primary }};text-transform:uppercase;letter-spacing:2px;margin-bottom:6px;">Fresh from the Oven</p>
            <h2 style="font-family:'{{ $fontDisplay }}',serif;font-size:22px;font-weight:700;color:{{ $text }};margin-bottom:8px;">Roti Artisan Segar Setiap Hari</h2>
            <p style="font-size:12px;color:{{ $text }}99;margin-bottom:14px;">Dibuat dengan cinta dan bahan-bahan berkualitas terbaik.</p>
            <span style="background:{{ $primary }};color:{{ $btnText }};font-size:12px;font-weight:600;padding:8px 20px;border-radius:{{ $radius }};display:inline-block;">Belanja Sekarang</span>
        </div>

        {{-- Product cards --}}
        <div style="padding:16px 20px;display:grid;grid-template-columns:repeat(3,1fr);gap:12px;">
            @foreach(['Croissant Butter','Donat Coklat','Red Velvet'] as $i => $name)
            <div style="background:{{ $cardBg }};border-radius:{{ $radius }};border:1px solid {{ $border }};box-shadow:{{ $shadow }};overflow:hidden;">
                <div style="background:{{ $accent }};height:70px;display:flex;align-items:center;justify-content:center;font-size:28px;">{{ ['🥐','🍩','🎂'][$i] }}</div>
                <div style="padding:10px;">
                    <p style="font-family:'{{ $fontDisplay }}',serif;font-size:12px;font-weight:700;color:{{ $text }};margin-bottom:3px;">{{ $name }}</p>
                    <p style="font-size:11px;color:{{ $primary }};font-weight:700;margin-bottom:7px;">Rp {{ number_format([18000,12000,85000][$i],0,',','.') }}</p>
                    <div style="background:{{ $primary }};color:{{ $btnText }};font-size:10px;font-weight:600;padding:4px;border-radius:{{ $radius }};text-align:center;">+ Keranjang</div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Footer --}}
        <div style="background:{{ $footerBg }};padding:14px 20px;display:flex;align-items:center;justify-content:space-between;">
            <span style="font-family:'{{ $fontDisplay }}',serif;font-size:14px;font-weight:700;color:{{ $btnText }};">Rabskubread</span>
            <span style="font-size:11px;color:{{ $btnText }}80;">© 2025 All rights reserved</span>
        </div>

    </div>
</div>
