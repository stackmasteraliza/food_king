<?php

namespace App\Services;

use Exception;
use App\Enums\Ask;
use Carbon\Carbon;
use App\Models\Item;
use App\Enums\Status;
use App\Models\Order;
use App\Enums\OrderStatus;
use App\Enums\Source;
use Illuminate\Support\Facades\Log;

class OrderStatusScreenOrderService
{
    public object $order;
    protected array $orderFilter = [
        'order_serial_no',
        'branch_id',
        'order_type',
        'status',
        'kitchen_status',
        'source'
    ];

    protected array $exceptFilter = [
        'excepts'
    ];

    /**
     * @throws Exception
     */
    public function list()
    {
        try {
            return Order::whereNotNull('token')->whereIn('status', [OrderStatus::PREPARING, OrderStatus::PREPARED])->where(function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereDate('order_datetime', Carbon::today())->where('is_advance_order', Ask::NO);
                })->orWhere(function ($subQuery) {
                    $subQuery->where('is_advance_order', Ask::YES)->where('order_datetime', '<', Carbon::today());
                });
            })->get();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function mostPopularItems()
    {
        try {
            return Item::with('media', 'category', 'offer')->withCount('orders')->where(['status' => Status::ACTIVE])->orderBy('orders_count', 'desc')->limit(9)->get();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}