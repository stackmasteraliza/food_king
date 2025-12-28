<?php

namespace App\Http\Resources;


use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class KitchenPrinterResource extends JsonResource
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
            "id"                => $this->id,
            "name"              => $this->name,
            "ip_address"       => $this->ip_address,
            "is_default"              => $this->is_default,
            'port' => $this->port,
            'branch_id'  => $this->branch_id
        ];
    }
}
