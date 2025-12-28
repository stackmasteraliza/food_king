<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Shift;
use App\Models\PosDevice;
use App\Models\PosShift;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Services\PosShiftService;
use App\Http\Requests\PosShiftRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\PosShiftResource;

class PosShiftController extends AdminController
{
    private PosShiftService $PosShiftService;

    public function __construct(PosShiftService $PosShift)
    {
        parent::__construct();
        $this->PosShiftService = $PosShift;
        $this->middleware(['permission:pos-shifts'])->only(
            'index',
            'store',
            'generateSessionNumber',
            'ConfirmPosShift',
            'ClosePosShift'
        );
    }
    public function index(
        PaginateRequest $request
    ): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return PosShiftResource::collection($this->PosShiftService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
    public function store(
        PosShiftRequest $request
    ): PosShiftResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new PosShiftResource($this->PosShiftService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function ClosePosShift(
        PosShiftRequest $request,
        PosShift $PosShift
    ): PosShiftResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new PosShiftResource($this->PosShiftService->ClosePosShift($request, $PosShift));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function ConfirmPosShift(
        PosShiftRequest $request,
        PosShift $PosShift
    ): PosShiftResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new PosShiftResource($this->PosShiftService->ConfirmPosShift($request, $PosShift));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
    private function generateSessionNumber($deviceId = null)
    {
        try {
            $device = $deviceId ? PosDevice::find($deviceId) : PosDevice::first();
            return response()->json($device->name . '/' . now()->format('Y/m/d') . '/' . Str::random(3));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
