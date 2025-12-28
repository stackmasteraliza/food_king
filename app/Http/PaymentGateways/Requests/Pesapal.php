<?php

namespace App\Http\PaymentGateways\Requests;

use App\Enums\Activity;
use Illuminate\Foundation\Http\FormRequest;

class Pesapal extends FormRequest
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
        if (request()->pesapal_status == Activity::ENABLE) {
            return [
                'pesapal_consumer_key'    => ['required', 'string'],
                'pesapal_consumer_secret' => ['required', 'string'],
                'pesapal_ipn_id'          => ['required', 'string'],
                'pesapal_mode'            => ['required', 'string'],
                'pesapal_status'          => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'pesapal_consumer_key'    => ['nullable', 'string'],
                'pesapal_consumer_secret' => ['nullable', 'string'],
                'pesapal_ipn_id'          => ['nullable', 'string'],
                'pesapal_mode'            => ['nullable', 'string'],
                'pesapal_status'          => ['nullable', 'numeric'],
            ];
        }
    }
}
