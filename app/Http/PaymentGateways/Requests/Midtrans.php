<?php

namespace App\Http\PaymentGateways\Requests;

use App\Enums\Activity;
use Illuminate\Foundation\Http\FormRequest;

class Midtrans extends FormRequest
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
        if (request()->midtrans_status == Activity::ENABLE) {
            return [
                'midtrans_server_key' => ['required', 'string'],
                'midtrans_client_key' => ['required', 'string'],
                'midtrans_mode'       => ['required', 'numeric'],
                'midtrans_status'     => ['nullable', 'numeric'],
            ];
        } else {
            return [
                'midtrans_server_key' => ['nullable', 'string'],
                'midtrans_client_key' => ['nullable', 'string'],
                'midtrans_mode'       => ['nullable', 'numeric'],
                'midtrans_status'     => ['nullable', 'numeric'],
            ];
        }
    }
}
