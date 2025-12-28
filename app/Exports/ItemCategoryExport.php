<?php

namespace App\Exports;

use App\Http\Requests\PaginateRequest;
use App\Services\ItemCategoryService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemCategoryExport implements FromCollection, WithHeadings
{

    public ItemCategoryService $itemCategoryService;
    public PaginateRequest $request;

    public function __construct(ItemCategoryService $itemCategoryService, $request)
    {
        $this->itemCategoryService = $itemCategoryService;
        $this->request            = $request;
    }

    public function collection(): \Illuminate\Support\Collection
    {
        $itemCategoryArray = [];
        $categories     = $this->itemCategoryService->list($this->request);

        foreach ($categories as $category) {
            $itemCategoryArray[] = [
                $category->name,
                trans('statuse.' . $category->status),
                $category->description
            ];
        }
        return collect($itemCategoryArray);
    }

    public function headings(): array
    {
        return [
            trans('all.label.name'),
            trans('all.label.status'),
            trans('all.label.description'),
        ];
    }
}