<?php

namespace App\Livewire\Customer\Product;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.customer')]
class ProductDetail extends Component
{
    public Product $product;
    
    public int $quantity = 1;
    
    public int $selectedImage = 0;

    public function mount(string $slug): void
    {
        $this->product = Product::query()
            ->active()
            ->with(['category', 'reviews.customer'])
            ->where('slug', $slug)
            ->firstOrFail();
        
        $this->product->increment('view_count');
    }

    public function addToCart(): void
    {
        if (!auth()->guard('customer')->check()) {
            $this->redirect(route('login'), navigate: true);
            return;
        }

        if (!$this->product->in_stock) {
            $this->dispatch('toast', message: 'Produk sedang habis', type: 'error');
            return;
        }

        $this->dispatch('toast', message: 'Berhasil ditambahkan ke keranjang', type: 'success');
    }

    public function toggleWishlist(): void
    {
        if (!auth()->guard('customer')->check()) {
            $this->redirect(route('login'), navigate: true);
            return;
        }

        $this->dispatch('toast', message: 'Berhasil ditambahkan ke wishlist', type: 'success');
    }

    public function getRelatedProductsProperty()
    {
        return Product::query()
            ->active()
            ->inStock()
            ->where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->limit(4)
            ->get();
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.customer.product.product-detail', [
            'relatedProducts' => $this->relatedProducts,
        ]);
    }
}
