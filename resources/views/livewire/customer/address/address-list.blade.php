<div class="min-h-screen bg-cream-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-neutral-900 mb-2">Alamat Saya</h1>
                    <p class="text-neutral-600">Kelola alamat pengiriman Anda</p>
                </div>
                <a href="{{ route('addresses.create') }}" class="btn-primary inline-flex items-center gap-2" wire:navigate>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Alamat
                </a>
            </div>
        </div>

        @if($addresses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($addresses as $address)
                    <div class="card relative {{ $address->is_default ? 'ring-2 ring-cream-400' : '' }}">
                        <!-- Default Badge -->
                        @if($address->is_default)
                            <div class="absolute top-4 right-4">
                                <span class="px-2 py-1 bg-cream-400 text-neutral-900 text-xs font-semibold rounded">
                                    Default
                                </span>
                            </div>
                        @endif

                        <div class="p-6">
                            <!-- Address Info -->
                            <div class="mb-4">
                                <h3 class="font-semibold text-neutral-900 mb-2">{{ $address->label }}</h3>
                                <div class="space-y-1 text-sm text-neutral-600">
                                    <p>{{ $address->street_address }}</p>
                                    <p>{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                                    <p>{{ $address->country }}</p>
                                    <p class="flex items-center gap-2 mt-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        {{ $address->phone }}
                                    </p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2 pt-4 border-t border-neutral-200">
                                <a href="{{ route('addresses.edit', $address->id) }}" class="btn-secondary flex-1 text-sm py-2 inline-flex items-center justify-center gap-1" wire:navigate>
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                                
                                @if(!$address->is_default)
                                    <button
                                        type="button"
                                        wire:click="setDefault({{ $address->id }})"
                                        class="btn-secondary flex-1 text-sm py-2 inline-flex items-center justify-center gap-1"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Jadikan Default
                                    </button>

                                    <button
                                        type="button"
                                        wire:click="delete({{ $address->id }})"
                                        wire:confirm="Apakah Anda yakin ingin menghapus alamat ini?"
                                        class="text-danger hover:bg-danger-50 px-3 py-2 rounded-lg transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <x-ui.empty-state
                icon="📍"
                title="Belum Ada Alamat"
                description="Tambahkan alamat pengiriman untuk memudahkan proses checkout"
                :href="route('addresses.create')"
                label="Tambah Alamat Pertama"
            />
        @endif
    </div>
</div>
