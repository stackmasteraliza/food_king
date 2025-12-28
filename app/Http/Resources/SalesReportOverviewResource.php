<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class SalesReportOverviewResource extends JsonResource
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
            "total_orders"           => $this->info['total_orders'],
            "total_earnings"         => $this->info['total_earnings'],
            "total_discounts"        => $this->info['total_discounts'],
            "total_delivery_charges" => $this->info['total_delivery_charges'],
            "total_cash"           => $this->info['total_cash'],
            "total_card"         => $this->info['total_card'],
            "total_mada"        => $this->info['total_mada'],
            "total_visa" => $this->info['total_visa'],
            "total_other" => $this->info['total_other'],
            "total_MOBILE_BANKING" => $this->info['total_MOBILE_BANKING'],
        ];
    }
}
