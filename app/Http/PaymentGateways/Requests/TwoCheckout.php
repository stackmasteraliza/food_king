<?php

namespace App\Http\PaymentGateways\Requests;

use App\Enums\Activity;
use Illuminate\Foundation\Http\FormRequest;

class TwoCheckout extends FormRequest
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
        if (request()->twocheckout_status == Activity::ENABLE) {
            return [
                'twocheckout_seller_id'            => ['required', 'string'],
                'twocheckout_secret_key'           => ['required', 'string'],
                'twocheckout_buy_link_secret_word' => ['required', 'string'],
                'twocheckout_mode'                 => ['required', 'numeric'],
                'twocheckout_status'               => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'twocheckout_seller_id'            => ['nullable', 'string'],
                'twocheckout_secret_key'           => ['nullable', 'string'],
                'twocheckout_buy_link_secret_word' => ['nullable', 'string'],
                'twocheckout_mode'                 => ['nullable', 'numeric'],
                'twocheckout_status'               => ['nullable', 'numeric'],
            ];
        }
    }
}