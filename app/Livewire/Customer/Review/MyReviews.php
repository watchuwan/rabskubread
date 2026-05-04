<?php

namespace App\Livewire\Customer\Review;

use App\Models\ProductReview;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.customer')]
class MyReviews extends Component
{
    use WithPagination;

    public function delete(int $reviewId): void
    {
        $review = ProductReview::where('customer_id', auth()->guard('customer')->id())
            ->findOrFail($reviewId);
        
        $review->delete();
        
        // Update product rating
        $review->product->updateRating();
        
        $this->dispatch('toast', message: 'Ulasan berhasil dihapus', type: 'success');
    }

    public function render()
    {
        $reviews = ProductReview::where('customer_id', auth()->guard('customer')->id())
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.customer.review.my-reviews', [
            'reviews' => $reviews,
        ]);
    }
}
