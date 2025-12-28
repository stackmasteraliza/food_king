<?php

namespace App\Http\Controllers\Auth;


use App\Enums\Ask;
use App\Models\User;
use App\Models\KioskMachine;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\KioskMachineResource;

class KioskMachineLoginController extends Controller
{
    public string $token;


    /**
     * @throws \Exception
     */

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $kioskMachine = KioskMachine::where('username', $request->post('username'))->first();

        if (!$kioskMachine || !Hash::check($request->post('password'), $kioskMachine->password)) {
            return response()->json([
                'errors' => ['validation' => trans('all.message.credentials_invalid')]
            ], 400);
        }

        if ($kioskMachine->is_login === Ask::YES) {
            return response()->json(['errors' => ['validation' => trans('all.message.already_logged_in')]], 400);
        }

        $user = User::find($kioskMachine->user_id);
        $this->token = $user->createToken('auth_token')->plainTextToken;
        $kioskMachine->update(['is_login' => Ask::YES]);

        return response()->json([
            'message'       => trans('all.message.login_success'),
            'token'         => $this->token,
            'kiosk' => new KioskMachineResource($kioskMachine),
        ], 201);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return new JsonResponse([
            'message' => trans('all.message.logout_success')
        ], 200);
    }
}