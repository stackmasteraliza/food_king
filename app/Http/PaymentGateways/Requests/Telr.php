<?php

namespace App\Http\PaymentGateways\Requests;

use App\Enums\Activity;
use Illuminate\Foundation\Http\FormRequest;

class Telr extends FormRequest
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
        if (request()->telr_status == Activity::ENABLE) {
            return [
                'telr_store_id'      => ['required', 'string'],
                'telr_store_auth_key' => ['required', 'string'],
                'telr_mode'             => ['required', 'string'],
                'telr_status'           => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'telr_store_id'      => ['nullable', 'string'],
                'telr_store_auth_key' => ['nullable', 'string'],
                'telr_mode'             => ['nullable', 'string'],
                'telr_status'           => ['nullable', 'numeric'],
            ];
        }
    }
}
