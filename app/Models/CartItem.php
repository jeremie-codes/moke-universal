<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'variant_id',
        'product_option_id',
        'variant_option_id',
        'delivery_type_id',
        'quantity',
        'price'
    ];

    protected $casts = [
        'price'=>'decimal:2'
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    public function productOption(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'product_option_id');
    }

    public function variantOption(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'variant_option_id');
    }

    public function deliveryType(): BelongsTo
    {
        return $this->belongsTo(DeliveryType::class);
    }

    public function availableStock(): int
    {
        $optionStocks = collect([$this->productOption?->stock, $this->variantOption?->stock])
            ->filter(fn ($stock) => $stock !== null);

        return $optionStocks->isNotEmpty()
            ? (int) $optionStocks->min()
            : (int) $this->product->stock;
    }
}
