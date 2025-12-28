<?php

namespace App\Services;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\TokenStoreRequest;
use App\Models\KioskMachine;

class TokenStoreService
{

    /**
     * @throws Exception
     */
    public function webToken(TokenStoreRequest $request)
    {
        try {

            $user = User::find(auth()->user()->id);
            $user->web_token = $request->token;
            $user->save();

            return true;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
    /**
     * @throws Exception
     */
    public function deviceToken(TokenStoreRequest $request)
    {
        try {

            $user = User::find(auth()->user()->id);
            $user->device_token = $request->token;
            $user->save();

            return true;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function kioskDeviceToken(TokenStoreRequest $request)
    {
        try {
            $kiosk = KioskMachine::where('user_id', auth()->user()->id)->where('machine_id', $request->machine_id)->first();
            if ($kiosk) {
                $kiosk->device_token = $request->token;
                $kiosk->save();
                return true;
            } else {
                throw new Exception(trans('all.message.kiosk_machine_not_found'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
