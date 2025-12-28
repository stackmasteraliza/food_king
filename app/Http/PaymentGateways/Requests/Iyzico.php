<?php

namespace App\Http\PaymentGateways\Requests;

use App\Enums\Activity;
use Illuminate\Foundation\Http\FormRequest;

class Iyzico extends FormRequest
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
        if (request()->iyzico_status == Activity::ENABLE) {
            return [
                'iyzico_api_key' => ['required', 'string'],
                'iyzico_secret_key' => ['required', 'string'],
                'iyzico_mode' => ['required', 'string'],
                'iyzico_status' => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'iyzico_api_key' => ['nullable', 'string'],
                'iyzico_secret_key' => ['nullable', 'string'],
                'iyzico_mode' => ['nullable', 'string'],
                'iyzico_status' => ['nullable', 'numeric'],
            ];
        }
    }
}
