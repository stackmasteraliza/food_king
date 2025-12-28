<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IntegrationRequest extends FormRequest
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
                Rule::unique("integrations", "name")->ignore($this->route('integration.id'))
            ],
            'price' => ['required', 'numeric'],
            'trial_days'     => ['nullable', 'numeric', 'min:0', 'max:60'],
            'icon_url'  => ['nullable', 'string',   'max:150'],
            'description'  => ['nullable', 'string',   'max:150'],
            'features'  => ['nullable', 'string',   'max:150'],
            'active'  => ['nullable', 'boolean']
        ];
    }
}
