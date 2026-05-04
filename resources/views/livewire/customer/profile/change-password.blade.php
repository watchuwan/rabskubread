<form wire:submit="updatePassword" class="space-y-6">
    <!-- Current Password -->
    <div>
        <label class="block text-sm font-medium text-neutral-700 mb-2">
            Password Saat Ini <span class="text-danger">*</span>
        </label>
        <input 
            wire:model="current_password"
            type="password"
            class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cream-500 focus:border-cream-500"
        />
        @error('current_password')
            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
        @enderror
    </div>

    <!-- New Password -->
    <div>
        <label class="block text-sm font-medium text-neutral-700 mb-2">
            Password Baru <span class="text-danger">*</span>
        </label>
        <input 
            wire:model="password"
            type="password"
            class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cream-500 focus:border-cream-500"
        />
        @error('password')
            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div>
        <label class="block text-sm font-medium text-neutral-700 mb-2">
            Konfirmasi Password Baru <span class="text-danger">*</span>
        </label>
        <input 
            wire:model="password_confirmation"
            type="password"
            class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cream-500 focus:border-cream-500"
        />
    </div>

    <!-- Submit Button -->
    <div class="pt-4">
        <button type="submit" class="btn-primary w-full md:w-auto">
            <svg class="w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Ubah Password
        </button>
    </div>
</form>
