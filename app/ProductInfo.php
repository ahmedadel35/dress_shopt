<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductInfo extends Model
{
    protected $guarded = [];

    protected $casts = ['more' => 'array'];

    /**
     * chunk info array
     *
     * @return array
     */
    public function getMiniAttribute(): array
    {
        $arr = $this->more;
        return array_splice($arr, 0, 4);
    }
}
