@props([
    'src' => null,
    'alt' => '',
    'class' => 'w-full h-full object-cover',
    'fallback' => '🥐',
    'fallbackSize' => 'text-3xl',
])

@if($src)
    <img src="{{ $src }}" alt="{{ $alt }}" class="{{ $class }}" />
@else
    <div class="w-full h-full flex items-center justify-center {{ $fallbackSize }}">
        {{ $fallback }}
    </div>
@endif
