<div class="mb-2">
    <p class="text-sm font-semibold text-gray-700 mb-3">Pilih Template Tema</p>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        @foreach(\App\Filament\Pages\ThemeCustomizer::PRESETS as $key => $preset)
        <button
            type="button"
            wire:click="applyPreset('{{ $key }}')"
            class="group flex flex-col items-center gap-2 p-3 rounded-xl border-2 border-gray-200 hover:border-gray-400 bg-white hover:bg-gray-50 transition-all cursor-pointer text-center"
        >
            {{-- Color swatch --}}
            <div class="flex gap-1">
                <span class="w-6 h-6 rounded-full border border-white shadow-sm" style="background: {{ $preset['primary'] }}"></span>
                <span class="w-6 h-6 rounded-full border border-white shadow-sm" style="background: {{ $preset['accent'] }}"></span>
            </div>
            <span class="text-xs font-medium text-gray-700 leading-tight">{{ $preset['emoji'] }} {{ $preset['label'] }}</span>
            <span class="text-[10px] text-gray-400">{{ $preset['font_sans'] }}</span>
        </button>
        @endforeach
    </div>
    <p class="text-xs text-gray-400 mt-2">Klik tema untuk mengisi form secara otomatis, lalu tekan <strong>Simpan Tampilan</strong>.</p>
</div>
