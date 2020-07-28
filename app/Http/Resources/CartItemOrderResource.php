<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        $product = $this->product;
        return [
            'id' => $this->id,
            'name' => $product->title,
            'description' => $product->info . "\n" . 'size: ' . $product->sizes[$this->size] . ' -- color: ' . $product->colors[$this->color] . "  \n#oRd" . $product->id . '__'. $this->size .'__' . $this->color,
            'amount_cents' => (int) (round($this->price, 2) *100),
            'quantity' => $this->qty
        ];
    }
}
