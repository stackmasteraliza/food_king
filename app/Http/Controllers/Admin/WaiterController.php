<?php

namespace App\Http\Controllers\Admin;

use App\Exports\WaiterExport;
use App\Http\Requests\WaiterRequest;
use App\Http\Resources\UserOrderResource;
use App\Http\Resources\WaiterResource;
use App\Services\WaiterService;
use Exception;
use App\Models\User;
use App\Services\OrderService;
use App\Exports\CustomerExport;
use App\Services\CustomerService;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\OrderResource;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\CustomerResource;
use App\Http\Requests\ChangeImageRequest;
use App\Http\Requests\UserChangePasswordRequest;

class WaiterController extends AdminController
{
    private WaiterService $waiterService;
    private OrderService $orderService;

    public function __construct(WaiterService $waiterService, OrderService $orderService)
    {
        parent::__construct();
        $this->waiterService = $waiterService;
        $this->orderService    = $orderService;
        $this->middleware(['permission:waiters'])->only(
            'index',
            'export',
            'changePassword',
            'changeImage',
            'myOrder'
        );
        $this->middleware(['permission:waiters_create'])->only('store');
        $this->middleware(['permission:waiters_edit'])->only('update');
        $this->middleware(['permission:waiters_delete'])->only('destroy');
        $this->middleware(['permission:waiters_show'])->only('show');
    }

    public function index(PaginateRequest $request
    ) : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return WaiterResource::collection($this->waiterService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(WaiterRequest $request
    ) : \Illuminate\Http\Response | WaiterResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new WaiterResource($this->waiterService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(
        WaiterRequest $request,
        User $waiter
    ) : \Illuminate\Http\Response | WaiterResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new WaiterResource($this->waiterService->update($request, $waiter));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(User $waiter
    ) : \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            $this->waiterService->destroy($waiter);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(User $waiter
    ) : \Illuminate\Http\Response | WaiterResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new WaiterResource($this->waiterService->show($waiter));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function export(PaginateRequest $request
    ) : \Illuminate\Http\Response | \Symfony\Component\HttpFoundation\BinaryFileResponse | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return Excel::download(new WaiterExport($this->waiterService, $request), 'Waiter.xlsx');
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function changePassword(
        UserChangePasswordRequest $request,
        User $waiter
    ) : \Illuminate\Http\Response | WaiterResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new WaiterResource($this->waiterService->changePassword($request, $waiter));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function changeImage(
        ChangeImageRequest $request,
        User $waiter
    ) : \Illuminate\Http\Response | WaiterResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new WaiterResource($this->waiterService->changeImage($request, $waiter));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function myOrder(
        PaginateRequest $request,
        User $waiter
    ) : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return UserOrderResource::collection($this->orderService->userOrder($request, $waiter));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
