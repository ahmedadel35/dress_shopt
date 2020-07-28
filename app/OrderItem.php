<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $guarded = [];

    protected $casts = [
        'qty' => 'int',
        'price' => 'float',
        'size' => 'int',
        'color' => 'int'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
