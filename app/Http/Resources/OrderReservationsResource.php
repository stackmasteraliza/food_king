<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use App\Libraries\AppLibrary;

class OrderReservationsResource extends JsonResource
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
            "id"         => $this->id,
            "customer_id"    => $this->customer_id,
            "ReservationsDate"      => AppLibrary::date($this->ReservationsDate),
            "ReservationsAmount"    => AppLibrary::currencyAmountFormat($this->ReservationsAmount),

        ];
    }
}
