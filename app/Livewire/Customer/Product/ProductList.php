<?php

namespace App\Livewire\Customer\Product;

use App\Models\Category;
use App\Models\Product;
use App\Models\Promotion;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.customer')]
class ProductList extends Component
{
    use WithPagination;

    #[Url]
    public ?string $category = null;

    #[Url]
    public string $search = '';

    #[Url]
    public ?int $minPrice = null;

    #[Url]
    public ?int $maxPrice = null;

    #[Url]
    public string $sortBy = 'latest';

    #[Url]
    public array $inStock = [];

    #[Url]
    public ?string $promotion = null; // 'bundle' | 'package' | null

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategory(): void
    {
        $this->resetPage();
    }

    public function updatingSortBy(): void
    {
        $this->resetPage();
    }

    public function toggleStock(): void
    {
        $this->inStock = $this->inStock ? [] : ['1'];
        $this->resetPage();
    }

    public function getProductsProperty()
    {
        $query = Product::query()
            ->active()
            ->with(['category', 'promotionItems.promotion' => fn ($q) => $q->valid()]);

        if ($this->search) {
            $query->search($this->search);
        }

        if ($this->category) {
            $query->byCategory((int) $this->category);
        }

        if ($this->minPrice) {
            $query->where('price', '>=', $this->minPrice);
        }
        if ($this->maxPrice) {
            $query->where('price', '<=', $this->maxPrice);
        }

        if ($this->inStock) {
            $query->inStock();
        }

        if ($this->promotion) {
            $query->whereHas('promotionItems.promotion', function ($q) {
                $q->valid()->where('type', $this->promotion);
            });
        }

        match ($this->sortBy) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'popular' => $query->orderBy('view_count', 'desc'),
            'rating' => $query->orderBy('rating_average', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        return $query->paginate(12)->withQueryString();
    }

    public function getCategoriesProperty()
    {
        return Category::active()
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();
    }

    public function clearFilters(): void
    {
        $this->category = null;
        $this->search = '';
        $this->minPrice = null;
        $this->maxPrice = null;
        $this->sortBy = 'latest';
        $this->inStock = [];
        $this->promotion = null;
        $this->resetPage();
    }

    public function getPromotionsProperty()
    {
        return Promotion::valid()->select('id', 'name', 'type')->get();
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.customer.product.product-list', [
            'products'   => $this->products,
            'categories' => $this->categories,
            'promotions' => $this->promotions,
        ]);
    }
}
