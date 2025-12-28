<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Http\Resources\CDSPopularItemResource;
use App\Http\Resources\CDSOrderDetailsResource;
use App\Services\OrderStatusScreenOrderService;

class OrderStatusScreenController extends AdminController
{
    private OrderStatusScreenOrderService $orderStatusScreenOrderService;

    public function __construct(OrderStatusScreenOrderService $orderStatusScreenOrderService)
    {
        parent::__construct();
        $this->orderStatusScreenOrderService = $orderStatusScreenOrderService;
        $this->middleware(['permission:order-status-screen'])->only('index', 'mostPopularItems');
    }

    public function index(): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return CDSOrderDetailsResource::collection($this->orderStatusScreenOrderService->list());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function mostPopularItems(): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return CDSPopularItemResource::collection($this->orderStatusScreenOrderService->mostPopularItems());
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}