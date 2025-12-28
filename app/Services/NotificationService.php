<?php

namespace App\Services;

use Exception;
use App\Libraries\AppLibrary;
use App\Models\NotificationSetting;
use Illuminate\Support\Facades\Log;
use Dipokhalder\EnvEditor\EnvEditor;
use Smartisan\Settings\Facades\Settings;
use App\Http\Requests\NotificationRequest;

class NotificationService
{
    public $envService;

    public function __construct(EnvEditor $envEditor)
    {
        $this->envService = $envEditor;
    }

    /**
     * @throws Exception
     */
    public function list()
    {
        try {
            return Settings::group('notification')->all();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @param NotificationRequest $request
     * @return
     * @throws Exception
     */
    public function update(NotificationRequest $request)
    {
        try {
            if (!$this->envService->getValue('DEMO')) {
                AppLibrary::fcmDataBind($request);
                Settings::group('notification')->set($request->validated());
                if ($request->notification_fcm_json_file) {
                    $newFilename = 'service-account-file' . '.' . $request->file('notification_fcm_json_file')->getClientOriginalExtension();
                    $setting = NotificationSetting::where('key', 'notification_fcm_json_file')->first();
                    $setting->clearMediaCollection('notification-file');
                    $setting->addMedia($request->file('notification_fcm_json_file'))->usingFileName($newFilename)->toMediaCollection('notification-file');
                }
                return $this->list();
            } else {
                throw new Exception(trans('all.message.feature_disable'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
