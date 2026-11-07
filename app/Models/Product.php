<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\Fit;

class Product extends Model implements HasMedia
{

    use InteractsWithMedia;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'type',
        'price',
        'old_price',
        'stock',
        'sku',
        'cover_image',
        'is_featured',
        'is_active',
        'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'price' => 'decimal:2',
        'old_price' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function ($product) {

            // Génération du slug
            $product->slug = Str::slug($product->title);

            // Génération du SKU
            $product->sku = self::generateSku();
        });

        static::updating(function ($product) {

            if ($product->isDirty('title')) {
                $product->slug = Str::slug($product->title);
            }

        });
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('images')
            ->useDisk('public');
    }


    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('thumb')
            ->fit(Fit::Contain, 100, 100);
    }

    protected static function generateSku(): string
    {
        return 'PRD-' . strtoupper(Str::random(8));
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }

    public function deliveryTypes(): BelongsToMany
    {
        return $this->belongsToMany(DeliveryType::class, 'product_delivery_types')
            ->withTimestamps();
    }

    public function availableStock(): int
    {
        $variantOptions = $this->variants
            ->flatMap(fn (Variant $variant) => $variant->options);
        $productOptions = $this->options;

        if ($variantOptions->isNotEmpty() && $productOptions->isNotEmpty()) {
            return min((int) $variantOptions->sum('stock'), (int) $productOptions->sum('stock'));
        }

        if ($variantOptions->isNotEmpty()) {
            return (int) $variantOptions->sum('stock');
        }

        if ($productOptions->isNotEmpty()) {
            return (int) $productOptions->sum('stock');
        }

        return (int) $this->stock;
    }

}
