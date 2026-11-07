<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DeliveryType extends Model
{
    protected $fillable = [
        'type',
        'label',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_delivery_types')
            ->withTimestamps();
    }
}
