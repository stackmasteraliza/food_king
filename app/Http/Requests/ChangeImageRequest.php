<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeImageRequest extends FormRequest
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
    public function rules()
    {
        return [
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048']
        ];
    }

    public function messages(): array
    {
        return [
            'image.image' => 'The image must be an image of type: jpg, jpeg, png.',
            'image.mimes' => 'The image must be an image of type: jpg, jpeg, png.'
        ];
    }
}