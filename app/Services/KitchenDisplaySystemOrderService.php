<?php

namespace App\Services;

use Exception;
use App\Enums\Ask;
use Carbon\Carbon;
use App\Models\Order;
use App\Enums\OrderStatus;
use App\Events\SendOrderSms;
use Illuminate\Http\Request;
use App\Events\SendOrderMail;
use App\Events\SendOrderPush;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\OrderStatusRequest;

class KitchenDisplaySystemOrderService
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
    public function list(Request $request)
    {
        try {
            $requests    = $request->all();
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_by') ?? 'desc';

            return Order::with('orderItems')->whereIn('status', [OrderStatus::ACCEPT, OrderStatus::PREPARING, OrderStatus::PREPARED])->where(function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereDate('order_datetime', Carbon::today())->where('is_advance_order', Ask::NO);
                })->orWhere(function ($subQuery) {
                    $subQuery->where('is_advance_order', Ask::YES)->whereDate('order_datetime',  Carbon::yesterday());
                });
            })->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->orderFilter)) {
                        if ($key === "status" && $request) {
                            $query->where($key, (int)$request);
                        } else {
                            $query->where($key, 'like', '%' . $request . '%');
                        }
                    }

                    if (in_array($key, $this->exceptFilter)) {
                        $explodes = explode('|', $request);
                        if (is_array($explodes)) {
                            foreach ($explodes as $explode) {
                                $query->where('order_type', '!=', $explode);
                            }
                        }
                    }
                }
            })->orderBy($orderColumn, $orderType)->get();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function changeStatus(Order $order, OrderStatusRequest $request)
    {
        try {
            SendOrderMail::dispatch(['order_id' => $order->id, 'status' => $request->status]);
            SendOrderSms::dispatch(['order_id' => $order->id, 'status' => $request->status]);
            SendOrderPush::dispatch(['order_id' => $order->id, 'status' => $request->status]);
            $order->status = $request->status;
            $order->save();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function OrderItems()
    {
        try {
            $orders = Order::with('orderItems')->where('status', OrderStatus::PREPARING)->where(function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereDate('order_datetime', Carbon::today())->where('is_advance_order', Ask::NO);
                })->orWhere(function ($subQuery) {
                    $subQuery->where('is_advance_order', Ask::YES)->whereDate('order_datetime',  Carbon::yesterday());
                });
            })->get();

            $allItems = $orders->pluck('orderItems')->flatten();
            $mergedItems = $allItems->groupBy(function ($item) {
                $variations = empty($item['item_variations']) ? '[]' : collect($item['item_variations'])->sortKeys()->toJson();
                $extras = empty($item['item_extras']) ? '[]' : collect($item['item_extras'])->sortKeys()->toJson();
                $instruction = $item['instruction'] ?? '';

                return json_encode([
                    'item_id' => $item['item_id'],
                    'item_variations' => $variations,
                    'item_extras' => $extras,
                    'instruction' => $instruction,
                ]);
            })->map(function ($groupedItems) {
                $firstItem = $groupedItems->first();
                $firstItem['quantity'] = $groupedItems->count() > 1 && !$firstItem['instruction'] ? $groupedItems->sum('quantity') : $firstItem['quantity'];
                return $firstItem;
            })->values();
            return $mergedItems;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}