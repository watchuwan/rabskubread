@props(['items'])
{{--
  $items = array of ['label' => '...', 'href' => '...', 'navigate' => true]
  Last item is current page (no href needed).
--}}

<nav class="mb-8" aria-label="Breadcrumb">
    <ol class="flex items-center space-x-2 text-sm text-neutral-500">
        <li>
            <a href="{{ route('home') }}" class="hover:text-neutral-900" wire:navigate>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </a>
        </li>
        @foreach($items as $item)
            <li>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </li>
            <li>
                @if(!empty($item['href']))
                    <a href="{{ $item['href'] }}" class="hover:text-neutral-900" @if(!empty($item['navigate'])) wire:navigate @endif>
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="text-neutral-900 font-medium">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
