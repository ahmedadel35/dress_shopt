<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $guarded = [];

    // protected $appends = ['total'];

    public function getTotalAttribute(): float
    {
        return round(
            $this->items->sum(function (CartItem $item) {
                return $item->sub_total;
            }),
            2
        );
    }

    public function getCountAttribute(): float
    {
        return round(
            $this->items->sum(function (CartItem $item) {
                return $item->qty;
            }),
            2
        );
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class)->with('product');
    }
}
