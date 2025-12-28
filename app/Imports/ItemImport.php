<?php

namespace App\Imports;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Tax;
use App\Rules\IniAmount;
use App\Libraries\EnumAppLibrary;
use DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;


class ItemImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        $category_id = $this->getCategoryId($this->sanitizeInput($row['category']));
        if ($category_id) {
            return new Item([
                'name' => $this->sanitizeInput($row['name'] ?? ''),
                'item_category_id' => $category_id,
                'slug' => Str::slug($this->sanitizeInput($row['name'])),
                'tax_id' => $this->getTaxId($row['tax']),
                'item_type' => EnumAppLibrary::itemType($this->sanitizeInput($row['item_type'] ?? '')),
                'price' => $row['price'],
                'is_featured' => EnumAppLibrary::itemFeature($row['featured']),
                'description' => $this->sanitizeInput($row['description'] ?? ''),
                'caution' => $this->sanitizeInput($row['caution'] ?? ''),
                'status' => EnumAppLibrary::itemStatus($row['status']),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:190',
                Rule::unique("items", "name")->whereNull('deleted_at')
            ],
            'category' => ['required', 'string'],
            'tax' => ['nullable', 'numeric'],
            'item_type' => ['required'],
            'price' => ['required', new IniAmount()],
            'featured' => ['required'],
            'description' => ['nullable', 'string', 'max:5000'],
            'caution' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', 'max:24'],
        ];
    }

    private function sanitizeInput($value): array|bool|string
    {
        return mb_convert_encoding(trim($value), 'UTF-8', 'UTF-8');
    }

    private function getTaxId($tax_rate): int|null
    {
        $tax = Tax::where('tax_rate', $tax_rate)->first();
        if ($tax) {
            return $tax->id;
        }
        return null;
    }

    private function getCategoryId($categoryName): int|null
    {
        $category = ItemCategory::where(DB::raw('LOWER(name)'), 'LIKE', '%' . strtolower($categoryName) . '%')->first();
        if ($category) {
            return $category->id;
        }

        return null;
    }

}