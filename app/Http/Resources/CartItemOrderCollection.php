<?php

namespace App\Http\Resources;

use App\CartItem;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CartItemOrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return CartItemOrderResource::collection($this->collection);
    }
}
