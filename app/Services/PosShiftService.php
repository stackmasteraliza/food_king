<?php

namespace App\Services;

use App\Enums\Status;
use Exception;
use App\Models\PosShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PosShiftRequest;
use App\Http\Requests\PaginateRequest;
use App\Libraries\QueryExceptionLibrary;
use Smartisan\Settings\Facades\Settings;
use App\Models\Shift;
use App\Models\PosDevice;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PosShiftService
{
    protected array $PosShiftFilter = [
        'shift_id',
        'pos_id',
        'session_number',
        'creator_id',
        'ended_at',
        'status',
        'closing_balance',
        'cashier_id'
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

            return PosShift::where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->PosShiftFilter)) {
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
    public function store(PosShiftRequest $request)
    {
        try {
            $deviceId = $request->pos_id ? PosDevice::find($request->pos_id) : PosDevice::first();
            $validated = $request->validate([
                'shift_id' => 'required|exists:shifts,id'
            ]);
            $session_number = PosShift::select('session_number')->where('shift_id', $request->shift_id)->whereNull('ended_at');
            if (!empty($session_number)) {
                return response()->json(['status' => false, 'message' => ':يوجد جلسة مفتوحة يرجى اغلاقها اولا', 'session_number' => $session_number], 422);
            }
            $session_number = $this->generateSessionNumber($deviceId);
            return   PosShift::create([
                'shift_id' => $request->shift_id,
                'pos_id' => $deviceId,
                'creator_id' => Auth::user()->id,
                'session_number' => $session_number,
                'started_at' => now(),
                'status' => 1,
                'cashier_id' => Auth::user()->id,

            ]);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    private function generateSessionNumber($deviceId = null)
    {
        $device = $deviceId ? PosDevice::find($deviceId) : PosDevice::first();
        return $device->name . '/' . now()->format('Y/m/d') . '/' . Str::random(3);
    }
    public function ClosePosShift(PosShiftRequest $request, PosShift $PosShift)
    {
        try {
            return  tap($PosShift)->update($request->validated());
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
    public function ConfirmPosShift(PosShiftRequest $request, PosShift $PosShift)
    {
        try {
            return  tap($PosShift)->update($request->validated());
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
    /**
     * @throws Exception
     */
    public function show(PosShift $PosShift): PosShift
    {
        try {
            return $PosShift;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
