<?php

namespace App\Livewire\Customer\Product;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ProductReviews extends Component
{
    public Product $product;
    
    public int $rating = 5;
    
    #[Validate('required|string|min:10|max:1000')]
    public string $review = '';
    
    public bool $showForm = false;
    
    public string $sortBy = 'latest';

    public function mount(Product $product): void
    {
        $this->product = $product;
    }

    public function getReviewsProperty()
    {
        $query = ProductReview::query()
            ->where('product_id', $this->product->id)
            ->where('is_approved', true)
            ->with('customer');

        match ($this->sortBy) {
            'highest' => $query->orderBy('rating', 'desc')->orderBy('created_at', 'desc'),
            'lowest' => $query->orderBy('rating', 'asc')->orderBy('created_at', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        return $query->get();
    }

    public function getRatingDistributionProperty()
    {
        $distribution = [
            5 => 0,
            4 => 0,
            3 => 0,
            2 => 0,
            1 => 0,
        ];

        foreach ($this->product->reviews as $review) {
            if ($review->is_approved && isset($distribution[$review->rating])) {
                $distribution[$review->rating]++;
            }
        }

        return $distribution;
    }

    public function canReview(): bool
    {
        if (!auth()->guard('customer')->check()) {
            return false;
        }

        $customer = auth()->guard('customer')->user();

        $hasPurchased = Order::query()
            ->where('customer_id', $customer->id)
            ->where('status', 'completed')
            ->whereHas('items', function ($query) {
                $query->where('product_id', $this->product->id);
            })
            ->exists();

        if (!$hasPurchased) {
            return false;
        }

        $alreadyReviewed = ProductReview::query()
            ->where('product_id', $this->product->id)
            ->where('customer_id', $customer->id)
            ->exists();

        return !$alreadyReviewed;
    }

    public function submitReview(): void
    {
        if (!auth()->guard('customer')->check()) {
            $this->redirect(route('login'), navigate: true);
            return;
        }

        if (!$this->canReview()) {
            $this->dispatch('toast', message: 'Anda belum memenuhi syarat untuk memberikan ulasan', type: 'error');
            return;
        }

        $this->validate();

        $customer = auth()->guard('customer')->user();

        ProductReview::create([
            'product_id' => $this->product->id,
            'customer_id' => $customer->id,
            'order_id' => Order::query()
                ->where('customer_id', $customer->id)
                ->where('status', 'completed')
                ->whereHas('items', function ($query) {
                    $query->where('product_id', $this->product->id);
                })
                ->latest()->first()->id ?? null,
            'rating' => $this->rating,
            'review' => $this->review,
            'is_approved' => false,
        ]);

        $this->reset(['review', 'showForm']);
        $this->dispatch('toast', message: 'Ulasan berhasil dikirim. Menunggu persetujuan admin.', type: 'success');
        $this->dispatch('refresh-product');
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.customer.product.product-reviews', [
            'reviews' => $this->reviews,
            'ratingDistribution' => $this->ratingDistribution,
            'canReview' => $this->canReview(),
        ]);
    }
}
