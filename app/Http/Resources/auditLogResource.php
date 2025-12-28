<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class auditLogResource extends JsonResource
{
    public $info;

    public function __construct($info)
    {
        parent::__construct($info);
        $this->info = $info;
    }

    public function toArray($request)
    {
        return [
            "model_type"           => $this->info['model_type'],
            "action_type"         => $this->info['action'],
            "before"        => $this->info['before'],
            "after" => $this->info['after'],
            "user"           => $this->info['user'],
            "description"         => $this->info['description'],
            "created_at"        => $this->info['created_at'],
            "ip_address" => $this->info['ip_address'],
            "user_agent" => $this->info['user_agent'],
            "seen" => $this->info['seen'],
        ];
    }
}
