@php
    $s = $settings ?? [];
    $primary     = $s['theme_primary_color']  ?? '#F59E0B';
    $hover       = $s['theme_primary_hover']  ?? '#D97706';
    $accent      = $s['theme_accent_color']   ?? '#FFE8A3';
    $bg          = $s['theme_bg_color']       ?? '#FFFEF9';
    $text        = $s['theme_text_color']     ?? '#1C1917';
    $navbarBg    = $s['theme_navbar_bg']      ?? '#FFFFFF';
    $footerBg    = $s['theme_footer_bg']      ?? '#1C1917';
    $cardBg      = $s['theme_card_bg']        ?? '#FFFFFF';
    $border      = $s['theme_border_color']   ?? '#E7E5E4';
    $btnText     = $s['theme_btn_text_color'] ?? '#FFFFFF';
    $heroFrom    = $s['theme_hero_from']      ?? '#FEF3C7';
    $heroTo      = $s['theme_hero_to']        ?? '#FFFEF9';
    $fontSans    = $s['theme_font_sans']      ?? 'Inter';
    $fontDisplay = $s['theme_font_display']   ?? 'Playfair Display';
    $fontSize    = $s['theme_font_size']      ?? 'base';

    $radiusMap = ['rounded-none'=>'0px','rounded'=>'4px','rounded-lg'=>'8px','rounded-xl'=>'12px','rounded-2xl'=>'16px','rounded-3xl'=>'24px'];
    $radius = $radiusMap[$s['theme_border_radius'] ?? 'rounded'] ?? '4px';

    $shadowMap = ['shadow-none'=>'none','shadow-sm'=>'0 1px 2px 0 rgb(0 0 0/.05)','shadow'=>'0 1px 3px 0 rgb(0 0 0/.1)','shadow-md'=>'0 4px 6px -1px rgb(0 0 0/.1)','shadow-lg'=>'0 10px 15px -3px rgb(0 0 0/.1)'];
    $shadow = $shadowMap[$s['theme_card_shadow'] ?? 'shadow-sm'] ?? $shadowMap['shadow-sm'];

    $fontSizeMap = ['sm'=>'14px','base'=>'16px','lg'=>'18px'];
    $baseFontSize = $fontSizeMap[$fontSize] ?? '16px';

    $fonts = collect([$fontSans, $fontDisplay])->unique()->map(fn($f) => urlencode($f).':ital,wght@0,400;0,600;0,700;1,400')->implode('&family=');
@endphp

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family={{ $fonts }}&display=swap" rel="stylesheet">

<style>
    :root {
        --color-primary:       {{ $primary }};
        --color-primary-hover: {{ $hover }};
        --color-accent:        {{ $accent }};
        --color-bg:            {{ $bg }};
        --color-text:          {{ $text }};
        --color-navbar-bg:     {{ $navbarBg }};
        --color-footer-bg:     {{ $footerBg }};
        --color-card-bg:       {{ $cardBg }};
        --color-border:        {{ $border }};
        --color-btn-text:      {{ $btnText }};
        --color-hero-from:     {{ $heroFrom }};
        --color-hero-to:       {{ $heroTo }};
        --font-sans:    '{{ $fontSans }}', ui-sans-serif, system-ui, sans-serif;
        --font-display: '{{ $fontDisplay }}', Georgia, serif;
        --font-size-base: {{ $baseFontSize }};
        --theme-radius: {{ $radius }};
        --theme-shadow: {{ $shadow }};

        /* Tailwind color overrides */
        --color-amber-100: {{ $accent }}80;
        --color-amber-200: {{ $accent }};
        --color-amber-400: {{ $primary }};
        --color-amber-500: {{ $primary }};
        --color-amber-600: {{ $hover }};
        --color-amber-700: {{ $hover }};
        --color-cream-50:  {{ $bg }};
        --color-cream-400: {{ $accent }};
        --color-cream-500: {{ $accent }};
        --color-neutral-900: {{ $text }};
    }

    html { font-size: var(--font-size-base); }
    body { background-color: {{ $bg }}; color: {{ $text }}; }

    /* Navbar */
    header, nav.site-header { background-color: {{ $navbarBg }} !important; }

    /* Footer */
    footer { background-color: {{ $footerBg }} !important; }

    /* Cards */
    .card, .card-hover, .product-card,
    [class*="bg-white"] { --tw-bg-opacity: 1; }
    .card, .card-hover { background-color: {{ $cardBg }} !important; border-radius: var(--theme-radius) !important; box-shadow: var(--theme-shadow) !important; }

    /* Buttons */
    .btn-primary { background-color: {{ $primary }} !important; color: {{ $btnText }} !important; border-radius: var(--theme-radius) !important; }
    .btn-primary:hover { background-color: {{ $hover }} !important; }
    .btn-secondary, .btn-ghost { border-radius: var(--theme-radius) !important; }

    /* Hero gradient */
    .hero-section, section.hero { background: linear-gradient(135deg, {{ $heroFrom }}, {{ $heroTo }}) !important; }

    /* Borders */
    .border-amber-100, .border-amber-200 { border-color: {{ $border }} !important; }
</style>
