<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
class Product extends Model implements HasMedia
{
    use HasFactory, HasSlug, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'low_stock_threshold',
        'sku',
        'ingredients',
        'allergens',
        'preparation_time',
        'is_customizable',
        'customization_options',
        'size',
        'calories',
        'is_active',
        'view_count',
        'rating_average',
        'review_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'ingredients' => 'array',
        'allergens' => 'array',
        'customization_options' => 'array',
        'is_active' => 'boolean',
        'is_customizable' => 'boolean',
        'rating_average' => 'decimal:2',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('product_images')
            ->useDisk('public')
            ->withResponsiveImages();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(200)->height(200)->nonQueued();

        $this->addMediaConversion('large')
            ->width(800)->height(800)->nonQueued();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function shippingMethods(): BelongsToMany
    {
        return $this->belongsToMany(ShippingMethod::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function promotionItems(): HasMany
    {
        return $this->hasMany(PromotionItem::class);
    }

    public function getMainImageAttribute(): ?string
    {
        return $this->getFirstMediaUrl('product_images', 'large') ?: null;
    }

    public function getThumbImageAttribute(): ?string
    {
        return $this->getFirstMediaUrl('product_images', 'thumb') ?: null;
    }

    public function getImageCountAttribute(): int
    {
        return $this->getMedia('product_images')->count();
    }

    public function getInStockAttribute(): bool
    {
        return $this->stock > 0;
    }

    public function getLowStockAttribute(): bool
    {
        return $this->stock > 0 && $this->stock <= $this->low_stock_threshold;
    }

    public function getPreparationTimeFormattedAttribute(): string
    {
        if (!$this->preparation_time) {
            return 'N/A';
        }
        if ($this->preparation_time >= 60) {
            $hours = floor($this->preparation_time / 60);
            $minutes = $this->preparation_time % 60;
            return "{$hours}h {$minutes}m";
        }
        return "{$this->preparation_time} minutes";
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->where('stock', '>', 0)
            ->whereColumn('stock', '<=', 'low_stock_threshold');
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearch($query, $search)
    {
        $term = '%' . strtolower($search) . '%';
        return $query->where(function ($q) use ($term) {
            $q->whereRaw('LOWER(name) LIKE ?', [$term])
                ->orWhereRaw('LOWER(description) LIKE ?', [$term])
                ->orWhereRaw('LOWER(sku) LIKE ?', [$term]);
        });
    }

    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    public function updateRating(): void
    {
        $stats = $this->reviews()->where('is_approved', true)
            ->selectRaw('AVG(rating) as avg, COUNT(*) as count')
            ->first();

        $this->update([
            'rating_average' => $stats->avg ?? 0,
            'review_count' => $stats->count ?? 0,
        ]);
    }

    public function decrementStock(int $quantity, ?string $referenceType = null, ?int $referenceId = null): bool
    {
        if ($this->stock < $quantity) {
            return false;
        }
        
        StockMovement::record($this, 'out', $quantity, $referenceType, $referenceId);
        $this->decrement('stock', $quantity);
        
        return true;
    }

    public function incrementStock(int $quantity, ?string $referenceType = null, ?int $referenceId = null): void
    {
        StockMovement::record($this, 'in', $quantity, $referenceType, $referenceId);
        $this->increment('stock', $quantity);
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }
}
