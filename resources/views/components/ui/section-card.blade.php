@props(['padding' => 'p-6', 'class' => ''])

<div class="bg-white rounded-2xl shadow-sm border border-neutral-200 {{ $padding }} {{ $class }}">
    {{ $slot }}
</div>
