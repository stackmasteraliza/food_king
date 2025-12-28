<?php

namespace App\Exports;


use App\Enums\Status;
use App\Services\CustomerService;
use App\Http\Requests\PaginateRequest;
use App\Services\WaiterService;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class WaiterExport implements FromCollection, WithHeadings
{

    public WaiterService $waiterService;
    public PaginateRequest $request;

    public function __construct(WaiterService $waiterService, $request)
    {
        $this->waiterService = $waiterService;
        $this->request         = $request;
    }

    public function collection() : \Illuminate\Support\Collection
    {
        $waiterArray = [];
        $waiters     = $this->waiterService->list($this->request);

        foreach ($waiters as $waiter) {
            $waiterArray[] = [
                $waiter->name,
                $waiter->email,
                $waiter->country_code . '' . $waiter->phone,
                trans('statuse.' . $waiter->status),
            ];
        }
        return collect($waiterArray);
    }

    public function headings() : array
    {
        return [
            trans('all.label.name'),
            trans('all.label.email'),
            trans('all.label.phone'),
            trans('all.label.status')
        ];
    }
}
