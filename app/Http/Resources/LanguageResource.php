<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class LanguageResource extends JsonResource
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
            "id"            => $this->id,
            "name"          => $this->name,
            "code"          => $this->code,
            "display_mode"  => $this->display_mode,
            "status"        => $this->status,
            'image'         => $this->image
        ];
    }
}
