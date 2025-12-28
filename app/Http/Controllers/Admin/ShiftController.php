<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Shift;
use Illuminate\Http\Request;
use App\Services\ShiftService;
use App\Http\Requests\ShiftRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\ShiftResource;

class ShiftController extends AdminController
{
    public ShiftService $ShiftService;

    public function __construct(ShiftService $Shift)
    {
        parent::__construct();
        $this->ShiftService = $Shift;
        $this->middleware(['permission:settings'])->only('store', 'update', 'destroy');
    }

    public function index(
        PaginateRequest $request
    ): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return ShiftResource::collection($this->ShiftService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function show(
        Shift $Shift
    ): ShiftResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new ShiftResource($this->ShiftService->show($Shift));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(
        ShiftRequest $request
    ): ShiftResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new ShiftResource($this->ShiftService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function update(
        ShiftRequest $request,
        Shift $Shift
    ): ShiftResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new ShiftResource($this->ShiftService->update($request, $Shift));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(
        Shift $Shift
    ): \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            $this->ShiftService->destroy($Shift);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
