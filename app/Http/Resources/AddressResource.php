<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
        return [
            // 'id' => $this->id,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'street' => $this->address,
            'building' => is_string($this->dep) ? $this->dep : 'NA',
            'city' => $this->city,
            'state' => $this->gov,
            'country' => $this->country,
            'email' => $this->userMail,
            'phone_number' => $this->phoneNumber,
            'postal_code' => $this->postCode,
            'extra_description' => is_string($this->notes) ? $this->notes : 'NA',
            'apartment' => 'NA',
            'floor' => 'NA'
        ];
    }
}
