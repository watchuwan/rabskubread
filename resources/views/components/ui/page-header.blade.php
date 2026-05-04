@props(['title', 'description' => null])

<div class="mb-8">
    <h1 class="text-3xl font-bold text-neutral-900 mb-2">{{ $title }}</h1>
    @if($description)
        <p class="text-neutral-600">{{ $description }}</p>
    @endif
</div>
