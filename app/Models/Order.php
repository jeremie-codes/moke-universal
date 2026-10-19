<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'reference',
        'fullname',
        'email',
        'phone',
        'address',
        'subtotal',
        'total',
        'status'
    ];

    protected $casts = [
        'subtotal'=>'decimal:2',
        'total'=>'decimal:2'
    ];

    protected static function booted(): void
    {
        static::creating(function ($order) {

            $order->reference = 'CMD-' . strtoupper(Str::random(8));

            $order->subtotal ??= 0;
            $order->total ??= 0;
            $order->status ??= 'pending';

        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
