<?php

namespace App\Http\Resources;


use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class CreditBalanceUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            "id"               => $this->id,
            "name"             => $this->name,
            "first_name"       => $this->FirstName,
            "last_name"        => $this->LastName,
            "phone"            => $this->phone,
            "email"            => $this->email,
            'username'         => $this->username,
            "balance"          => AppLibrary::flatAmountFormat($this->balance),
            "currency_balance" => AppLibrary::currencyAmountFormat($this->balance),
            "country_code"     => $this->country_code,
        ];
    }
}
