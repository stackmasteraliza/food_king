<?php

namespace App\Http\Resources;


use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class SimpleOrderResource extends JsonResource
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
            'id'                           => $this->id,
            'order_serial_no'              => $this->order_serial_no,
            'order_datetime'               => AppLibrary::datetime($this->order_datetime),
            "total_currency_price"         => AppLibrary::currencyAmountFormat($this->total),
            "total_amount_price"           => AppLibrary::flatAmountFormat($this->total),
            "discount_amount_price"        => AppLibrary::flatAmountFormat($this->discount),
            "delivery_charge_amount_price" => AppLibrary::flatAmountFormat($this->delivery_charge),
            'payment_method'               => $this->payment_method,
            'payment_status'               => $this->payment_status,
            'transaction'                  => $this->transaction ? strtoupper($this->transaction?->payment_method) : null,
            'order_type'                   => $this->order_type,
            'source'                       => $this->source,
            'pos_payment_method'           => $this->pos_payment_method,
            'status'                       => $this->status,
            'status_name'                  => trans('orderStatus.' . $this->status),
            'customer_name'                => $this->user?->name,
        ];
    }
}
