<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\WaiterAddressRequest;
use App\Services\UserAddressService;
use Exception;
use App\Models\User;
use App\Models\Address;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\AddressResource;

class WaiterAddressController extends AdminController
{

    private UserAddressService $userAddressService;

    public function __construct(UserAddressService $userAddressService)
    {
        parent::__construct();
        $this->userAddressService = $userAddressService;
        $this->middleware(['permission:waiters_show'])->only('index', 'store', 'update', 'destroy', 'show');
    }

    public function index(PaginateRequest $request, User $waiter): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return AddressResource::collection($this->userAddressService->list($request, $waiter));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(WaiterAddressRequest $request, User $waiter): \Illuminate\Http\Response | AddressResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new AddressResource($this->userAddressService->store($request, $waiter));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(WaiterAddressRequest $request, User $waiter, Address $address): \Illuminate\Http\Response | AddressResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new AddressResource($this->userAddressService->update($request, $waiter, $address));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(User $waiter, Address $address): \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->userAddressService->destroy($waiter, $address);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(User $waiter, Address $address): \Illuminate\Http\Response | AddressResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new AddressResource($this->userAddressService->show($waiter, $address));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
