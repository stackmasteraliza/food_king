<?php

namespace App\Http\Controllers\Admin;
use App\Exports\ChefExport;
use App\Http\Requests\ChefRequest;
use App\Http\Resources\ChefResource;
use App\Http\Resources\UserOrderResource;
use App\Services\ChefService;
use Exception;
use App\Models\User;
use App\Services\OrderService;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\ChangeImageRequest;
use App\Http\Requests\UserChangePasswordRequest;

class ChefController extends AdminController
{
    private ChefService $chefService;
    private OrderService $orderService;

    public function __construct(ChefService $chefService, OrderService $orderService)
    {
        parent::__construct();
        $this->chefService = $chefService;
        $this->orderService = $orderService;
        $this->middleware(['permission:chefs'])->only(
            'index',
            'export',
            'changePassword',
            'changeImage',
            'myOrder'
        );
        $this->middleware(['permission:chefs_create'])->only('store');
        $this->middleware(['permission:chefs_edit'])->only('update');
        $this->middleware(['permission:chefs_delete'])->only('destroy');
        $this->middleware(['permission:chefs_show'])->only('show');
    }

    public function index(PaginateRequest $request
    ): \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return ChefResource::collection($this->chefService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(ChefRequest $request
    ): \Illuminate\Http\Response|ChefResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new ChefResource($this->chefService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(
        ChefRequest $request,
        User $chef
    ): \Illuminate\Http\Response|ChefResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new ChefResource($this->chefService->update($request, $chef));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(User $chef
    ): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory {
        try {
            $this->chefService->destroy($chef);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(User $chef
    ): \Illuminate\Http\Response|ChefResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new ChefResource($this->chefService->show($chef));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function export(PaginateRequest $request
    ): \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return Excel::download(new ChefExport($this->chefService, $request), 'Chef.xlsx');
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function changePassword(
        UserChangePasswordRequest $request,
        User $chef
    ): \Illuminate\Http\Response|ChefResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new ChefResource($this->chefService->changePassword($request, $chef));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function changeImage(
        ChangeImageRequest $request,
        User $chef
    ): \Illuminate\Http\Response|ChefResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new ChefResource($this->chefService->changeImage($request, $chef));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function myOrder(
        PaginateRequest $request,
        User $chef
    ): \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return UserOrderResource::collection($this->orderService->userOrder($request, $chef));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
