<?php

namespace App\Http\Resources;


use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class IntegrationResource extends JsonResource
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
            "icon_url"              => $this->icon_url,
            "description"       => $this->description,
            "price"            => $this->price,
            "trial_days"              => $this->trial_days,
            "features" => $this->features,
            "active"     =>  $this->active,

            "userIntegration" => $this->whenLoaded('userIntegration', function () {
                return $this->userIntegration->map(function ($userIntegration) {
                    return [
                        "api_url" => $userIntegration->api_url,
                        "secret_key" => $userIntegration->secret_key,
                        "api_key" => $userIntegration->api_key,
                        "client_id" => $userIntegration->client_id,
                        "username" => $userIntegration->username,
                        "password" => $userIntegration->password,
                        "auth_method" => $userIntegration->auth_method,

                    ];
                });
            }),

        ];
    }
}
