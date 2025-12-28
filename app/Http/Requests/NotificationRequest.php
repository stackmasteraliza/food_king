<?php

namespace App\Http\Requests;

use App\Models\NotificationSetting;
use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
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
            'notification_fcm_api_key'             => ['required', 'string', 'max:500'],
            'notification_fcm_public_vapid_key'    => ['required', 'string', 'max:900'],
            'notification_fcm_auth_domain'         => ['required', 'string', 'max:500'],
            'notification_fcm_project_id'          => ['required', 'string', 'max:500'],
            'notification_fcm_storage_bucket'      => ['required', 'string', 'max:500'],
            'notification_fcm_messaging_sender_id' => ['required', 'string', 'max:500'],
            'notification_fcm_app_id'              => ['required', 'string', 'max:500'],
            'notification_fcm_measurement_id'      => ['required', 'string', 'max:500'],
            'notification_fcm_json_file'           => ['nullable', 'file', 'mimes:json', 'max:2048']
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (empty(request('notification_fcm_json_file'))) {
                $notification = NotificationSetting::where(['key' => 'notification_fcm_json_file'])->first()->file;
                if (empty($notification)) {
                    $validator->errors()->add('notification_fcm_json_file', 'The file field is required');
                }
            }
        });
    }
}
