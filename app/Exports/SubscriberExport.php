<?php

namespace App\Exports;

use App\Enums\DiscountType;
use App\Http\Requests\PaginateRequest;
use App\Libraries\AppLibrary;
use App\Services\SubscriberService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubscriberExport implements FromCollection, WithHeadings
{

    public SubscriberService $subscriberService;
    public PaginateRequest $request;

    public function __construct(SubscriberService $subscriberService, $request)
    {
        $this->subscriberService = $subscriberService;
        $this->request            = $request;
    }

    public function collection(): \Illuminate\Support\Collection
    {
        $subscriberArray = [];
        $subscribersArray     = $this->subscriberService->list($this->request);

        foreach ($subscribersArray as $coupon) {
            $subscriberArray[] = [
                $coupon->email,
                AppLibrary::datetime($coupon->created_at),
            ];
        }
        return collect($subscriberArray);
    }

    public function headings(): array
    {
        return [
            trans('all.label.email'),
            trans('all.label.date'),
        ];
    }
}