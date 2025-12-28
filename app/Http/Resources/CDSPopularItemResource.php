<?php

namespace App\Http\Resources;


use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class CDSPopularItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $price = $this->price;
        return [
            "id"             => $this->id,
            "name"           => $this->name,
            "currency_price" => AppLibrary::currencyAmountFormat($this->price),
            "thumb"          => $this->thumb,
        ];
    }
}