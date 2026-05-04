<div
    x-data="{
        show: false,
        title: '',
        message: '',
        confirmText: 'Hapus',
        cancelText: 'Batal',
        onConfirm: null,
        init() {
            window.addEventListener('confirm-modal', (event) => {
                this.title = event.detail.title || 'Konfirmasi';
                this.message = event.detail.message || 'Apakah Anda yakin?';
                this.confirmText = event.detail.confirmText || 'Hapus';
                this.cancelText = event.detail.cancelText || 'Batal';
                this.onConfirm = event.detail.onConfirm;
                this.show = true;
            });
        },
        confirm() {
            if (this.onConfirm) {
                window.dispatchEvent(new CustomEvent(this.onConfirm));
            }
            this.show = false;
        },
        cancel() {
            this.show = false;
        }
    }"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
>
    <!-- Backdrop -->
    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm"
        @click="cancel()"
    ></div>

    <!-- Modal -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6"
            @click.stop
        >
            <!-- Icon -->
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-amber-100 rounded-full">
                <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <!-- Title -->
            <h3 class="text-lg font-semibold text-neutral-900 text-center mb-2" x-text="title"></h3>

            <!-- Message -->
            <p class="text-sm text-neutral-600 text-center mb-6" x-text="message"></p>

            <!-- Actions -->
            <div class="flex gap-3">
                <button
                    @click="cancel()"
                    class="flex-1 px-4 py-2.5 text-sm font-medium text-neutral-700 bg-neutral-100 hover:bg-neutral-200 rounded-lg transition-colors"
                    x-text="cancelText"
                ></button>
                <button
                    @click="confirm()"
                    class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors"
                    x-text="confirmText"
                ></button>
            </div>
        </div>
    </div>
</div>
