<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CDSOrderDetailsResource extends JsonResource
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
            'id'                                  => $this->id,
            'order_serial_no'                     => $this->order_serial_no,
            'token'                               => $this->token,
            'order_type'                          => $this->order_type,
            'status'                              => $this->status,
        ];
    }
}