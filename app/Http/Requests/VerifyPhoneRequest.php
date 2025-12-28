<?php

namespace App\Http\Requests;


use App\Rules\ValidPhone;
use Illuminate\Foundation\Http\FormRequest;

class VerifyPhoneRequest extends FormRequest
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
            'code'  => ['required', 'numeric'],
            'phone' => ['required', 'string', 'max:180', new ValidPhone()],
            'token' => ['required', 'max:180'],
        ];
    }
}