<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'reference',
        'provider',
        'phone',
        'method',
        'amount',
        'currency',
        'status',
        'transaction_id',
        'response',
        'paid_at'
    ];

    protected $casts = [
        'amount'=>'decimal:2',
        'response'=>'array',
        'paid_at'=>'datetime'
    ];

    protected static function booted(): void
    {
        static::creating(function ($payment) {

            $payment->reference = 'PAY-' . strtoupper(Str::random(8));

            $payment->status ??= 'pending';

        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
