<div>
    @if($sent)
        <div class="p-6 bg-success/10 border border-success rounded-xl text-center">
            <div class="text-4xl mb-3">✅</div>
            <h3 class="font-semibold text-neutral-900 mb-1">Pesan Terkirim!</h3>
            <p class="text-sm text-neutral-600">Terima kasih, kami akan segera menghubungi Anda.</p>
            <button wire:click="$set('sent', false)" class="btn-secondary mt-4 text-sm">Kirim Pesan Lain</button>
        </div>
    @else
        <form wire:submit="submit" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Nama Lengkap <span class="text-danger">*</span></label>
                <input wire:model="name" type="text" placeholder="John Doe"
                       class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cream-500 focus:border-cream-500 @error('name') border-danger @enderror" />
                @error('name') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Email <span class="text-danger">*</span></label>
                <input wire:model="email" type="email" placeholder="nama@email.com"
                       class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cream-500 focus:border-cream-500 @error('email') border-danger @enderror" />
                @error('email') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Subjek <span class="text-danger">*</span></label>
                <input wire:model="subject" type="text" placeholder="Pertanyaan tentang produk"
                       class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cream-500 focus:border-cream-500 @error('subject') border-danger @enderror" />
                @error('subject') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Pesan <span class="text-danger">*</span></label>
                <textarea wire:model="message" rows="4" placeholder="Tulis pesan Anda di sini..."
                          class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cream-500 focus:border-cream-500 @error('message') border-danger @enderror"></textarea>
                @error('message') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn-primary w-full" wire:loading.attr="disabled">
                <span wire:loading.remove>
                    <svg class="w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Kirim Pesan
                </span>
                <span wire:loading>Mengirim...</span>
            </button>
        </form>
    @endif
</div>
