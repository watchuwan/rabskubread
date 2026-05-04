<div class="min-h-screen bg-cream-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-ui.page-header title="Profil Saya" description="Kelola informasi profil dan preferensi akun Anda" />

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Left Sidebar (Avatar & Stats) -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Avatar Upload Card -->
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-neutral-900 mb-4">Foto Profil</h3>
                    
                    <div class="flex flex-col items-center">
                        <!-- Current Avatar -->
                        <div class="relative mb-4">
                            <div class="w-32 h-32 rounded-full overflow-hidden bg-cream-100 border-4 border-cream-200 shadow-lg">
                                @if($avatar && Str::startsWith($avatar, 'http'))
                                    <img src="{{ $avatar }}" alt="{{ $name }}" class="w-full h-full object-cover" />
                                @elseif($avatar)
                                    <img src="{{ Storage::url($avatar) }}" alt="{{ $name }}" class="w-full h-full object-cover" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="text-5xl font-bold text-cream-600">
                                            {{ strtoupper(substr($name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Upload Button -->
                            <label class="absolute bottom-0 right-0 w-10 h-10 bg-cream-500 rounded-full flex items-center justify-center cursor-pointer hover:bg-cream-600 transition-colors shadow-lg border-4 border-white">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                </svg>
                                <input type="file" wire:model="avatar" class="hidden" accept="image/*" />
                            </label>
                        </div>
                        
                        <!-- Upload Progress -->
                        @if($avatar instanceof \Livewire\UploadedFile)
                            <div class="w-full bg-neutral-200 rounded-full h-2 mb-3">
                                <div class="bg-cream-500 h-2 rounded-full transition-all duration-300" 
                                     style="width: {{ $avatar->progress() }}%"></div>
                            </div>
                            <p class="text-xs text-neutral-500 mb-3">Uploading... {{ $avatar->progress() }}%</p>
                        @endif
                        
                        <!-- Action Buttons -->
                        @if($avatar instanceof \Livewire\UploadedFile)
                            <div class="flex gap-2 mb-3">
                                <button wire:click="updateAvatar" wire:loading.attr="disabled" class="btn-primary text-sm px-3 py-1.5 flex-1">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Simpan
                                </button>
                                <button wire:click="$set('avatar', null)" class="btn-secondary text-sm px-3 py-1.5 flex-1">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Batal
                                </button>
                            </div>
                        @elseif($avatar)
                            <button wire:click="removeAvatar" class="btn-secondary text-sm px-3 py-1.5 w-full mb-3">
                                <svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus Foto
                            </button>
                        @endif
                        
                        <!-- Help Text -->
                        <p class="text-xs text-neutral-500 text-center">
                            Format: JPG, PNG, GIF<br/>
                            Max: 2MB • Recommended: 200x200px
                        </p>
                    </div>
                </div>

                <!-- Stats Card -->
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-neutral-900 mb-4">Statistik</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-cream-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-cream-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-neutral-900">Pesanan</p>
                                    <p class="text-xs text-neutral-500">Total</p>
                                </div>
                            </div>
                            <p class="text-lg font-bold text-neutral-900">{{ $totalOrders }}</p>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-cream-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-cream-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-neutral-900">Wishlist</p>
                                    <p class="text-xs text-neutral-500">Favorit</p>
                                </div>
                            </div>
                            <p class="text-lg font-bold text-neutral-900">{{ $totalWishlist }}</p>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-cream-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-cream-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-neutral-900">Ulasan</p>
                                    <p class="text-xs text-neutral-500">Review</p>
                                </div>
                            </div>
                            <p class="text-lg font-bold text-neutral-900">{{ $totalReviews }}</p>
                        </div>
                    </div>
                    
                    <!-- Member Since -->
                    <div class="mt-4 pt-4 border-t border-neutral-200">
                        <p class="text-xs text-neutral-500 text-center">
                            Anggota sejak {{ $customer->created_at->format('M Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Main Content (Forms) -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Profile Information Form -->
                <div class="card p-6">
                    <h2 class="text-xl font-semibold text-neutral-900 mb-6">Informasi Pribadi</h2>

                    <form wire:submit="updateProfile" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-neutral-700 mb-2">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <input wire:model="name" type="text" class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cream-500 focus:border-cream-500" placeholder="John Doe" />
                                @error('name')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-neutral-700 mb-2">
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input wire:model="email" type="email" class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cream-500 focus:border-cream-500" placeholder="nama@email.com" />
                                @error('email')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-2">
                                    Nomor Telepon
                                </label>
                                <input wire:model="phone" type="tel" placeholder="0812-3456-7890" class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cream-500 focus:border-cream-500" />
                                @error('phone')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Birth Date -->
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-2">
                                    Tanggal Lahir
                                </label>
                                <input wire:model="birth_date" type="date" class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cream-500 focus:border-cream-500" />
                                @error('birth_date')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-neutral-700 mb-3">
                                    Jenis Kelamin
                                </label>
                                <div class="grid grid-cols-3 gap-3">
                                    <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all {{ $gender === 'male' ? 'border-cream-500 bg-cream-50' : 'border-neutral-200 hover:border-cream-300' }}">
                                        <input type="radio" wire:model="gender" value="male" class="sr-only" />
                                        <span class="text-2xl mr-3">👨</span>
                                        <span class="text-sm font-medium">Laki-laki</span>
                                        @if($gender === 'male')
                                            <svg class="absolute top-2 right-2 w-5 h-5 text-cream-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </label>
                                    <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all {{ $gender === 'female' ? 'border-cream-500 bg-cream-50' : 'border-neutral-200 hover:border-cream-300' }}">
                                        <input type="radio" wire:model="gender" value="female" class="sr-only" />
                                        <span class="text-2xl mr-3">👩</span>
                                        <span class="text-sm font-medium">Perempuan</span>
                                        @if($gender === 'female')
                                            <svg class="absolute top-2 right-2 w-5 h-5 text-cream-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </label>
                                    <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all {{ $gender === 'other' ? 'border-cream-500 bg-cream-50' : 'border-neutral-200 hover:border-cream-300' }}">
                                        <input type="radio" wire:model="gender" value="other" class="sr-only" />
                                        <span class="text-2xl mr-3">👤</span>
                                        <span class="text-sm font-medium">Lainnya</span>
                                        @if($gender === 'other')
                                            <svg class="absolute top-2 right-2 w-5 h-5 text-cream-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </label>
                                </div>
                                @error('gender')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4 border-t border-neutral-200">
                            <button type="submit" class="btn-primary w-full md:w-auto">
                                <svg class="w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password Card -->
                <div class="card p-6">
                    <h2 class="text-xl font-semibold text-neutral-900 mb-6">Keamanan Akun</h2>
                    <livewire:customer.profile.change-password />
                </div>
            </div>
        </div>
    </div>
</div>
