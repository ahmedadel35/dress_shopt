<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $guarded = [];

    protected $appends = [
        'sub_total'
    ];

    protected $casts = [
        'qty' => 'int',
        'price' => 'float',
        'size' => 'int',
        'color' => 'int'
    ];

    public function getSubTotalAttribute(): float
    {
        return round($this->price * $this->qty, 2);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }
}
