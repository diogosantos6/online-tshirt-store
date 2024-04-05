<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "nif" => $this->nif,
            "address" => $this->address,
            "default_payment_type" => $this->default_payment_type,
            "default_payment_reference" => $this->default_payment_reference
        ];
    }
}
