<?php

namespace App\Services;

use Exception;
use App\Enums\Ask;
use App\Enums\Status;
use App\Models\KioskMachine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\KioskMachineRequest;

class KioskMachineService
{
    public object $machine;
    protected array $kioskMachineFilter = [
        'user_id',
        'branch_id',
        'machine_id',
        'username',
        'status'
    ];

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            return KioskMachine::where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->kioskMachineFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function store(KioskMachineRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $this->machine = KioskMachine::create([
                    'machine_id' => $request->machine_id,
                    'user_id'    => $request->user_id,
                    'username'   => $request->username,
                    'password'   => bcrypt($request->password),
                    'branch_id'  => $request->branch_id,
                    'status'     => $request->status,
                ]);
            });
            return $this->machine;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(KioskMachineRequest $request, KioskMachine $kioskMachine)
    {
        try {
            DB::transaction(function () use ($kioskMachine, $request) {
                $this->machine             = $kioskMachine;
                $this->machine->machine_id = $request->machine_id;
                $this->machine->user_id    = $request->user_id;
                $this->machine->username   = $request->username;
                $this->machine->branch_id  = $request->branch_id;
                $this->machine->status     = $request->status;
                if ($request->password) {
                    $this->machine->password = Hash::make($request->password);
                }
                $this->machine->save();
            });
            return $this->machine;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(KioskMachine $kioskMachine): void
    {
        try {
            DB::transaction(function () use ($kioskMachine) {
                $pushNotification = (object)[
                    'title'       => 'Kiosk Notification',
                    'description' => "Logged Out Successfully.",
                ];
                $fcmTokenArray = [];
                if (!blank($kioskMachine->device_token)) {
                    $fcmTokenArray[] = $kioskMachine->device_token;
                }
                $firebase         = new FirebaseService();
                $firebase->sendNotification($pushNotification, $fcmTokenArray, "kiosk-logout-notification");
                $kioskMachine->delete();
            });
        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(KioskMachine $kioskMachine): KioskMachine
    {
        try {
            return $kioskMachine;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function changeStatus(KioskMachine $kioskMachine, Request $request): KioskMachine
    {
        try {
            DB::transaction(function () use ($kioskMachine, $request) {
                if ($request->filled('status')) {
                    $kioskMachine->update(['status' => $request->input('status')]);
                }
                $pushNotification = (object)[
                    'title'       => 'Kiosk Notification',
                    'description' => "Status Updated Successfully.",
                ];
                $fcmTokenArray = [];
                if (!blank($kioskMachine->device_token)) {
                    $fcmTokenArray[] = $kioskMachine->device_token;
                }
                $firebase         = new FirebaseService();
                $firebase->sendNotification($pushNotification, $fcmTokenArray, $kioskMachine->status === Status::ACTIVE ? "kiosk-status-on" : "kiosk-status-off");
            });
            return $kioskMachine;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function logout(KioskMachine $kioskMachine)
    {
        try {
            DB::transaction(function () use ($kioskMachine) {
                $pushNotification = (object)[
                    'title'       => 'Kiosk Notification',
                    'description' => "Logged Out Successfully.",
                ];
                $fcmTokenArray = [];
                if (!blank($kioskMachine->device_token)) {
                    $fcmTokenArray[] = $kioskMachine->device_token;
                }
                $firebase         = new FirebaseService();
                $firebase->sendNotification($pushNotification, $fcmTokenArray, "kiosk-logout-notification");
                $kioskMachine->update(['is_login' => Ask::NO]);
            });
        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}