<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'product_option_id',
        'variant_option_id',
        'delivery_type_id',
        'variant_name',
        'product_option_name',
        'variant_option_name',
        'delivery_type_label',
        'price',
        'quantity'
    ];

    protected $casts = [
        'price'=>'decimal:2'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
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
}
