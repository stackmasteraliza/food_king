<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\KioskMachine;
use Illuminate\Http\Request;
use App\Services\KioskMachineService;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\KioskMachineRequest;
use App\Http\Resources\KioskMachineResource;

class KioskMachineController extends AdminController
{

    public KioskMachineService $kioskMachineService;

    public function __construct(KioskMachineService $kioskMachineService)
    {
        parent::__construct();
        $this->kioskMachineService = $kioskMachineService;
        $this->middleware(['permission:settings'])->only('show', 'store', 'update', 'destroy', 'logout', 'changeStatus');
    }

    public function index(PaginateRequest $request): \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return KioskMachineResource::collection($this->kioskMachineService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(KioskMachineRequest $request): \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\Foundation\Application|KioskMachineResource
    {
        try {
            return new KioskMachineResource($this->kioskMachineService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(KioskMachineRequest $request, KioskMachine $kioskMachine): \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\Foundation\Application|KioskMachineResource
    {
        try {
            return new KioskMachineResource($this->kioskMachineService->update($request, $kioskMachine));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(KioskMachine $kioskMachine): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->kioskMachineService->destroy($kioskMachine);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(KioskMachine $kioskMachine): \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\Foundation\Application|KioskMachineResource
    {
        try {
            return new KioskMachineResource($this->kioskMachineService->show($kioskMachine));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function logout(KioskMachine $kioskMachine): \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\Foundation\Application|KioskMachineResource
    {
        try {
            $this->kioskMachineService->logout($kioskMachine);
            return response(trans('all.message.logout_success'), 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function changeStatus(KioskMachine $kioskMachine, Request $request): \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->kioskMachineService->changeStatus($kioskMachine, $request);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
