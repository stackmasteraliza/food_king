<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\PosDevice;
use Illuminate\Http\Request;
use App\Services\PosDeviceService;
use App\Http\Requests\PosDeviceRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\PosDeviceResource;

class PosDeviceController extends AdminController
{
    public PosDeviceService $PosDeviceService;

    public function __construct(PosDeviceService $PosDevice)
    {
        parent::__construct();
        $this->PosDeviceService = $PosDevice;
        $this->middleware(['permission:settings'])->only('store', 'update', 'destroy');
    }

    public function index(
        PaginateRequest $request
    ): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return PosDeviceResource::collection($this->PosDeviceService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function show(
        PosDevice $PosDevice
    ): PosDeviceResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new PosDeviceResource($this->PosDeviceService->show($PosDevice));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(
        PosDeviceRequest $request
    ): PosDeviceResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new PosDeviceResource($this->PosDeviceService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function update(
        PosDeviceRequest $request,
        PosDevice $PosDevice
    ): PosDeviceResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new PosDeviceResource($this->PosDeviceService->update($request, $PosDevice));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(
        PosDevice $PosDevice
    ): \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            $this->PosDeviceService->destroy($PosDevice);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
