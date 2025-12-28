<?php

namespace App\Http\Resources;


use App\Enums\Status;
use App\Libraries\AppLibrary;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SimpleItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $price = $this->price;
        return [
            "id"             => $this->id,
            "name"           => $this->name,
            "slug"           => $this->slug,
            "item_category_id" => $this->item_category_id,
            "tax_id"           => $this->tax_id,
            "is_featured"      => $this->is_featured,
            "flat_price"     => AppLibrary::flatAmountFormat($this->price),
            "convert_price"  => AppLibrary::convertAmountFormat($this->price),
            "currency_price" => AppLibrary::currencyAmountFormat($this->price),
            "price"          => $this->price,
            "item_type"      => $this->item_type,
            "status"         => $this->status,
            "description"    => $this->description === null ? '' : $this->description,
            "caution"        => $this->caution === null ? '' : $this->caution,
            "thumb"          => $this->thumb,
            "cover"          => $this->cover,
            "preview"        => $this->preview,
            "category_name"  => optional($this->category)->name,
            "offer"          => SimpleOfferResource::collection(
                $this->offer->filter(function ($offer) use ($price) {
                    if (Carbon::now()->between(
                            $offer->start_date,
                            $offer->end_date
                        ) && $offer->status === Status::ACTIVE) {
                        $offer->flat_price     = AppLibrary::flatAmountFormat($price - ($price / 100 * $offer->amount));
                        $offer->convert_price  = AppLibrary::convertAmountFormat(
                            $price - ($price / 100 * $offer->amount)
                        );
                        $offer->currency_price = AppLibrary::currencyAmountFormat(
                            $price - ($price / 100 * $offer->amount)
                        );
                        return $offer;
                    }
                })
            )
        ];
    }
}
