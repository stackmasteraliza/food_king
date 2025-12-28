<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PosShiftRequest extends FormRequest
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
        //'shift_id' => $this->shift_id,
        // 'pos_id' => $this->pos_id,
        // 'session_number' => $this->session_number,
        // 'creator_id' => $this->creator_id,
        // 'ended_at' => $this->ended_at,
        // 'editor_id' => $this->editor_id,
        // 'started_at' => $this->started_at,
        // 'userConfirm_id' => $this->userConfirm_id,
        // 'dateConfirm' => $this->dateConfirms
        return [
            'shift_id'      => [
                'required',
                'integer',
            ],
            'pos_id'    => ['required', 'integer'],
            'session_number'    => ['required', 'string'],
            'creator_id'    => ['required', 'integer'],
            'cashier_id'    => ['required', 'integer']
        ];
    }
}
