<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class KioskMachineResource extends JsonResource
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
            "id"          => $this->id,
            "user_id"     => $this->user_id,
            "branch_id"   => $this->branch_id,
            "machine_id"  => $this->machine_id,
            "username"    => $this->username,
            "user_name"   => $this->user?->name,
            "branch_name" => $this->branch?->name,
            "is_login"    => $this->is_login,
            "status"      => $this->status,
        ];
    }
}
