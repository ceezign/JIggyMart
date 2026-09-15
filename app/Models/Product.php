<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING = 'pending';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_OUT_OF_STOCK = 'out_of_stock';
    public const STATUS_SUSPENDED = 'suspended';
    public const STATUS_ARCHIVED = 'archived';

    protected $fillable = [
        'seller_id', 'category_id', 'name', 'slug', 'description', 'brand', 'sku',
        'price', 'discount_price', 'stock_quantity', 'condition', 'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'discount_price' => 'decimal:2',
            'average_rating' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name).'-'.Str::random(6);
            }
        });
    }

    // ----- Relationships -----
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): HasMany
    {
        return $this->hasMany(ProductImage::class)->where('is_primary', true);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    // ----- Accessors -----
    public function getEffectivePriceAttribute(): float
    {
        return (float) ($this->discount_price ?? $this->price);
    }

    public function getIsInStockAttribute(): bool
    {
        return $this->stock_quantity > 0 && $this->status === self::STATUS_ACTIVE;
    }

    // ----- Scopes -----
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeSearch($query, ?string $term)
    {
        if (! $term) {
            return $query;
        }

        $like = '%'.str_replace(['%', '_'], ['\%', '\_'], $term).'%';

        return $query->where(function ($q) use ($like) {
            $q->where('name', 'like', $like)
                ->orWhere('description', 'like', $like)
                ->orWhere('brand', 'like', $like)
                ->orWhere('sku', 'like', $like)
                ->orWhereHas('category', fn ($cq) => $cq->where('name', 'like', $like));
        });
    }

    public function scopeCategory($query, ?int $categoryId)
    {
        if (! $categoryId) {
            return $query;
        }

        $category = Category::find($categoryId);

        if (! $category) {
            return $query->whereRaw('1 = 0'); // unknown category id: match nothing, don't silently show everything
        }

        return $query->whereIn('category_id', $category->selfAndChildIds());
    }

    public function scopeSeller($query, ?int $sellerId)
    {
        return $sellerId ? $query->where('seller_id', $sellerId) : $query;
    }

    public function scopePriceBetween($query, ?float $min, ?float $max)
    {
        if ($min !== null) {
            $query->where(fn ($q) => $q->whereRaw('COALESCE(discount_price, price) >= ?', [$min]));
        }
        if ($max !== null) {
            $query->where(fn ($q) => $q->whereRaw('COALESCE(discount_price, price) <= ?', [$max]));
        }

        return $query;
    }

    public function scopeCondition($query, ?string $condition)
    {
        return $condition ? $query->where('condition', $condition) : $query;
    }

    public function scopeMinRating($query, ?float $rating)
    {
        return $rating ? $query->where('average_rating', '>=', $rating) : $query;
    }

    public function scopeBrand($query, ?string $brand)
    {
        return $brand ? $query->where('brand', $brand) : $query;
    }

    public function scopeAvailable($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeSorted($query, ?string $sort)
    {
        return match ($sort) {
            'newest' => $query->orderByDesc('created_at'),
            'price_asc' => $query->orderByRaw('COALESCE(discount_price, price) asc'),
            'price_desc' => $query->orderByRaw('COALESCE(discount_price, price) desc'),
            'rating' => $query->orderByDesc('average_rating'),
            'popular' => $query->orderByDesc('sales_count'),
            default => $query->orderByDesc('created_at'),
        };
    }
}
