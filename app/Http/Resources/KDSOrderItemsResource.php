<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class KDSOrderItemsResource extends JsonResource
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
            'item_id'         => $this->item_id,
            'item_name'       => $this->orderItem?->name,
            'quantity'        => $this->quantity,
            'item_variations' => json_decode($this->item_variations),
            'item_extras'     => json_decode($this->item_extras),
            'instruction'     => $this->instruction,
        ];
    }
}
