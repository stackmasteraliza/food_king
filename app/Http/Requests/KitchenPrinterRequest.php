<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KitchenPrinterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'              => [
                'required',
                'string',
                'max:100',
                Rule::unique("kitchen_printers", "name")->ignore($this->route('KitchenPrinterSetting.id'))
            ],
            'ip_address'            => ['required', 'string', 'max:100'],
            'is_default' => ['required', 'integer', 'max:10'],
            'port' => ['required', 'integer', 'max:9999'],
            'branch_id' => ['required', 'integer'],
        ];
    }
}
