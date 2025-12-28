<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class PosShiftResource extends JsonResource
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
            "id"        => $this->id,
            'shift_id' => $this->shift_id,
            'pos_id' => $this->pos_id,
            'session_number' => $this->session_number,
            'creator_id' => $this->creator_id,
            'ended_at' => $this->ended_at,
            'editor_id' => $this->editor_id,
            'started_at' => $this->started_at,
            'userConfirm_id' => $this->userConfirm_id,
            'dateConfirm' => $this->dateConfirm,
            'cashier_id' => $this->cashier_id,
            'closing_balance' => $this->closing_balance,
            'status' => $this->status,

        ];
    }
}
