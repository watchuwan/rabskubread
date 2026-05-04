@props([
    'icon' => null,       // emoji string, e.g. "📦"
    'title',
    'description' => null,
    'href' => null,
    'label' => null,
])

<div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-12 text-center">
    @if($icon)
        <div class="text-6xl mb-4">{{ $icon }}</div>
    @endif
    <h3 class="text-xl font-semibold text-neutral-900 mb-2">{{ $title }}</h3>
    @if($description)
        <p class="text-neutral-600 mb-6">{{ $description }}</p>
    @endif
    @if($href && $label)
        <a href="{{ $href }}" class="btn-primary inline-flex items-center gap-2" wire:navigate>
            {{ $label }}
        </a>
    @endif
    {{ $slot }}
</div>
