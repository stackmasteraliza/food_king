<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\Tax;
use App\Models\Item;
use App\Models\User;
use App\Models\Order;
use App\Enums\TaxType;
use App\Models\Address;
use App\Enums\OrderType;
use App\Models\OrderItem;
use App\Enums\OrderStatus;
use App\Models\OrderCoupon;
use App\Models\Transaction;
use App\Enums\PaymentStatus;
use App\Events\SendOrderSms;
use App\Models\OrderAddress;
use Illuminate\Http\Request;
use App\Events\SendOrderMail;
use App\Events\SendOrderPush;
use App\Libraries\AppLibrary;
use App\Models\FrontendOrder;
use App\Models\PaymentGateway;
use App\Events\SendOrderGotSms;
use App\Events\SendOrderGotMail;
use App\Events\SendOrderGotPush;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OrderRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\PosOrderRequest;
use App\Events\SendOrderDeliveryBoySms;
use App\Events\SendOrderDeliveryBoyMail;
use App\Events\SendOrderDeliveryBoyPush;
use App\Http\Requests\TableOrderRequest;
use Smartisan\Settings\Facades\Settings;
use App\Http\Requests\OrderStatusRequest;
use App\Http\Requests\PaymentStatusRequest;
use App\Http\Requests\TableOrderTokenRequest;
use App\Models\OrderReservationsData;
use App\Events\OrderConfirmed;

class OrderService
{
    public object $order;
    protected array $orderFilter = [
        'order_serial_no',
        'user_id',
        'branch_id',
        'total',
        'order_type',
        'order_datetime',
        'payment_method',
        'payment_status',
        'status',
        'delivery_boy_id',
        'source',
        'creator_id'
    ];

    protected array $exceptFilter = [
        'excepts'
    ];

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_by') ?? 'desc';

            return Order::with('transaction', 'orderItems', 'branch', 'user', 'creator')->where(function ($query) use ($requests) {
                if (isset($requests['from_date']) && isset($requests['to_date'])) {
                    $first_date = Date('Y-m-d', strtotime($requests['from_date']));
                    $last_date  = Date('Y-m-d', strtotime($requests['to_date']));
                    $query->whereDate('order_datetime', '>=', $first_date)->whereDate(
                        'order_datetime',
                        '<=',
                        $last_date
                    );
                }
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->orderFilter)) {
                        if ($key === "status") {
                            $query->where($key, (int)$request);
                        } else if ($key === 'payment_method') {
                            if ((int)$request > 0) {
                                if ((int)$request === 1) {
                                    $query->where('payment_method', 1)->where('pos_payment_method', null)->whereDoesntHave('transaction');
                                } else {
                                    $paymentGateway = PaymentGateway::findOrFail((int)$request);
                                    $query->whereHas('transaction', function ($q) use ($paymentGateway) {
                                        $q->where('payment_method', $paymentGateway->slug);
                                    });
                                }
                            } else {
                                $query->where('pos_payment_method', abs((int)$request));
                            }
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

                // Add condition for "exceptSource"
                if (isset($requests['exceptSource'])) {
                    $query->where('source', '!=', $requests['exceptSource']);
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function userOrder(PaginateRequest $request, User $user)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_by') ?? 'desc';

            return Order::with('transaction', 'orderItems', 'branch', 'user', 'creator')->where('order_type', "!=", OrderType::POS)->where(function ($query) use ($requests, $user) {
                $query->where('user_id', $user->id);
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->orderFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                    if (in_array($key, $this->exceptFilter)) {
                        $explodes = explode('|', $request);
                        if (is_array($explodes)) {
                            foreach ($explodes as $explode) {
                                $query->where('status', '!=', $explode);
                            }
                        }
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function deliveredOrder(PaginateRequest $request, User $user)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_by') ?? 'desc';

            return Order::where('delivery_boy_id', $user->id)->where('order_type', "!=", OrderType::POS)->where(
                function ($query) use ($requests) {
                    foreach ($requests as $key => $request) {
                        if (in_array($key, $this->orderFilter)) {
                            $query->where($key, 'like', '%' . $request . '%');
                        }
                        if (in_array($key, $this->exceptFilter)) {
                            $explodes = explode('|', $request);
                            if (is_array($explodes)) {
                                foreach ($explodes as $explode) {
                                    $query->where('status', '!=', $explode);
                                }
                            }
                        }
                    }
                }
            )->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function deliveryBoyOrder(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_by') ?? 'desc';

            return Order::where('order_type', "!=", OrderType::POS)->where('delivery_boy_id', Auth::user()->id)->where(
                function ($query) use ($requests) {
                    foreach ($requests as $key => $request) {
                        if (in_array($key, $this->orderFilter)) {
                            $query->where($key, 'like', '%' . $request . '%');
                        }
                        if (in_array($key, $this->exceptFilter)) {
                            $explodes = explode('|', $request);
                            if (is_array($explodes)) {
                                foreach ($explodes as $explode) {
                                    $query->where('status', '!=', $explode);
                                }
                            }
                        }
                    }
                }
            )->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function myOrderStore(OrderRequest $request): object
    {
        try {
            DB::transaction(function () use ($request) {
                $this->order = Order::create(
                    $request->validated() + [
                        'user_id'          => Auth::user()->id,
                        'creator_id' => Auth::user()->id,
                        'status'           => OrderStatus::PENDING,
                        'order_datetime'   => date('Y-m-d H:i:s'),
                        'preparation_time' => Settings::group('order_setup')->get('order_setup_food_preparation_time')
                    ]
                );

                $i            = 0;
                $totalTax     = 0;
                $itemsArray   = [];
                $requestItems = json_decode($request->items);
                $items        = Item::get()->pluck('tax_id', 'id');
                $taxes        = AppLibrary::pluck(Tax::get(), 'obj', 'id');

                if (!blank($requestItems)) {
                    foreach ($requestItems as $item) {
                        $taxId          = isset($items[$item->item_id]) ? $items[$item->item_id] : 0;
                        $taxName        = isset($taxes[$taxId]) ? $taxes[$taxId]->name : null;
                        $taxRate        = isset($taxes[$taxId]) ? $taxes[$taxId]->tax_rate : 0;
                        $taxType        = isset($taxes[$taxId]) ? $taxes[$taxId]->type : TaxType::FIXED;
                        $taxPrice       = $taxType === TaxType::FIXED ? $taxRate : ($item->total_price * $taxRate) / 100;
                        $itemsArray[$i] = [
                            'order_id'             => $this->order->id,
                            'branch_id'            => $item->branch_id,
                            'item_id'              => $item->item_id,
                            'quantity'             => $item->quantity,
                            'discount'             => (float)$item->discount,
                            'tax_name'             => $taxName,
                            'tax_rate'             => $taxRate,
                            'tax_type'             => $taxType,
                            'tax_amount'           => $taxPrice,
                            'price'                => $item->item_price,
                            'item_variations'      => json_encode($item->item_variations),
                            'item_extras'          => json_encode($item->item_extras),
                            'instruction'          => $item->instruction,
                            'item_variation_total' => $item->item_variation_total,
                            'item_extra_total'     => $item->item_extra_total,
                            'total_price'          => $item->total_price,
                        ];
                        $totalTax       = $totalTax + $taxPrice;
                        $i++;
                    }
                }

                if (!blank($itemsArray)) {
                    OrderItem::insert($itemsArray);
                }

                $this->order->order_serial_no = date('dmy') . $this->order->id;
                $this->order->total_tax       = $totalTax;
                $this->order->save();

                if ($request->address_id) {
                    $address = Address::find($request->address_id);
                    if ($address) {
                        OrderAddress::create([
                            'order_id'  => $this->order->id,
                            'user_id'   => Auth::user()->id,
                            'label'     => $address->label,
                            'address'   => $address->address,
                            'apartment' => $address->apartment,
                            'latitude'  => $address->latitude,
                            'longitude' => $address->longitude
                        ]);
                    }
                }
                if ($request->Reservations) {
                    OrderReservationsData::create([
                        'order_id'  => $this->order->id,
                        'customer_id'   => Auth::user()->id,
                        'ReservationsDate'     => $request->reservationDate,
                        'ReservationsAmount'   => $request->reservationamount

                    ]);
                }
                if ($request->coupon_id > 0) {
                    OrderCoupon::create([
                        'order_id'  => $this->order->id,
                        'coupon_id' => $request->coupon_id,
                        'user_id'   => Auth::user()->id,
                        'discount'  => $request->discount
                    ]);
                }

                SendOrderMail::dispatch(['order_id' => $this->order->id, 'status' => $request->status]);
                SendOrderSms::dispatch(['order_id' => $this->order->id, 'status' => $request->status]);
                SendOrderPush::dispatch(['order_id' => $this->order->id, 'status' => $request->status]);
                try {

                    event(new OrderConfirmed($this->order));
                } catch (Exception $exception) {

                    Log::info($exception->getMessage());
                }
            });
            return $this->order;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
    public function getMaxToken(PosOrderRequest $request)
    {
        // $branchId = $request->input('branch_id');

        $maxToken = Order::max('token');

        return $maxToken;
    }
    /**
     * @throws Exception
     */
    public function posOrderStore(PosOrderRequest $request): object
    {
        try {

            DB::transaction(function () use ($request) {
                $maxToken = Order::max('token');
                $request->token = $maxToken + 1;
                $this->order = Order::create(
                    $request->validated() + [
                        'user_id'          => $request->customer_id,
                        'creator_id' => Auth::user()->id,
                        'status'           => OrderStatus::ACCEPT,
                        'token'            =>  $request->token,
                        'payment_status'   => PaymentStatus::PAID,
                        'order_datetime'   => date('Y-m-d H:i:s'),
                        'preparation_time' => Settings::group('order_setup')->get('order_setup_food_preparation_time')
                    ]
                );

                $i            = 0;
                $totalTax     = 0;
                $itemsArray   = [];
                $requestItems = json_decode($request->items);
                $items        = Item::get()->pluck('tax_id', 'id');
                $taxes        = AppLibrary::pluck(Tax::get(), 'obj', 'id');

                if (!blank($requestItems)) {
                    foreach ($requestItems as $item) {
                        $taxId          = isset($items[$item->item_id]) ? $items[$item->item_id] : 0;
                        $taxName        = isset($taxes[$taxId]) ? $taxes[$taxId]->name : null;
                        $taxRate        = isset($taxes[$taxId]) ? $taxes[$taxId]->tax_rate : 0;
                        $taxType        = isset($taxes[$taxId]) ? $taxes[$taxId]->type : TaxType::FIXED;
                        $taxPrice       = $taxType === TaxType::FIXED ? $taxRate : ($item->total_price * $taxRate) / 100;
                        $itemsArray[$i] = [
                            'order_id'             => $this->order->id,
                            'branch_id'            => $item->branch_id,
                            'item_id'              => $item->item_id,
                            'quantity'             => $item->quantity,
                            'discount'             => (float)$item->discount,
                            'tax_name'             => $taxName,
                            'tax_rate'             => $taxRate,
                            'tax_type'             => $taxType,
                            'tax_amount'           => $taxPrice,
                            'price'                => $item->item_price,
                            'item_variations'      => json_encode($item->item_variations),
                            'item_extras'          => json_encode($item->item_extras),
                            'instruction'          => $item->instruction,
                            'item_variation_total' => $item->item_variation_total,
                            'item_extra_total'     => $item->item_extra_total,
                            'total_price'          => $item->total_price,
                        ];
                        $totalTax       = $totalTax + $taxPrice;
                        $i++;
                    }
                }


                if (!blank($itemsArray)) {
                    OrderItem::insert($itemsArray);
                }

                $this->order->order_serial_no = date('dmy') . $this->order->id;
                $this->order->total_tax       = $totalTax;
                $currentTime = Carbon::now();
                $endTime = $currentTime->copy()->addMinutes(Settings::group('order_setup')->get('order_setup_schedule_order_slot_duration'));
                $start = $currentTime->format('H:i');
                $end = $endTime->format('H:i');
                $this->order->delivery_time   = "$start - $end";
                $this->order->token   = $request->token;
                $this->order->save();

                //storing order address
                if ($request->address_id) {
                    $address = Address::find($request->address_id);
                    if ($address) {
                        OrderAddress::create([
                            'order_id'  => $this->order->id,
                            'user_id'   => $request->customer_id,
                            'label'     => $address->label,
                            'address'   => $address->address,
                            'apartment' => $address->apartment,
                            'latitude'  => $address->latitude,
                            'longitude' => $address->longitude
                        ]);
                    }
                }
                //storing order address
                if ($request->Reservations) {
                    OrderReservationsData::create([
                        'order_id'  => $this->order->id,
                        'customer_id'   => $request->customer_id,
                        'ReservationsDate'     => $request->reservationDate,
                        'ReservationsAmount'   => $request->reservationamount

                    ]);
                }
                try {

                    event(new OrderConfirmed($this->order));
                } catch (Exception $exception) {

                    Log::info($exception->getMessage());
                }
            });
            return $this->order;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }


    /**
     * @throws Exception
     */
    public function tableOrderStore(TableOrderRequest $request): object
    {
        try {
            DB::transaction(function () use ($request) {
                $this->order = FrontendOrder::create(
                    $request->validated() + [
                        'user_id'          => $request->customer_id,
                        'creator_id' => Auth::user()->id,
                        'status'           => OrderStatus::PENDING,
                        'order_datetime'   => date('Y-m-d H:i:s'),
                        'preparation_time' => Settings::group('order_setup')->get('order_setup_food_preparation_time')
                    ]
                );

                $i            = 0;
                $totalTax     = 0;
                $itemsArray   = [];
                $requestItems = json_decode($request->items);
                $items        = Item::get()->pluck('tax_id', 'id');
                $taxes        = AppLibrary::pluck(Tax::get(), 'obj', 'id');

                if (!blank($requestItems)) {
                    foreach ($requestItems as $item) {
                        $taxId          = isset($items[$item->item_id]) ? $items[$item->item_id] : 0;
                        $taxName        = isset($taxes[$taxId]) ? $taxes[$taxId]->name : null;
                        $taxRate        = isset($taxes[$taxId]) ? $taxes[$taxId]->tax_rate : 0;
                        $taxType        = isset($taxes[$taxId]) ? $taxes[$taxId]->type : TaxType::FIXED;
                        $taxPrice       = $taxType === TaxType::FIXED ? $taxRate : ($item->total_price * $taxRate) / 100;
                        $itemsArray[$i] = [
                            'order_id'             => $this->order->id,
                            'branch_id'            => $item->branch_id,
                            'item_id'              => $item->item_id,
                            'quantity'             => $item->quantity,
                            'discount'             => (float)$item->discount,
                            'tax_name'             => $taxName,
                            'tax_rate'             => $taxRate,
                            'tax_type'             => $taxType,
                            'tax_amount'           => $taxPrice,
                            'price'                => $item->item_price,
                            'item_variations'      => json_encode($item->item_variations),
                            'item_extras'          => json_encode($item->item_extras),
                            'instruction'          => $item->instruction,
                            'item_variation_total' => $item->item_variation_total,
                            'item_extra_total'     => $item->item_extra_total,
                            'total_price'          => $item->total_price,
                        ];
                        $totalTax       = $totalTax + $taxPrice;
                        $i++;
                    }
                }

                if (!blank($itemsArray)) {
                    OrderItem::insert($itemsArray);
                }

                $this->order->order_serial_no = date('dmy') . $this->order->id;
                $this->order->total_tax       = $totalTax;
                $this->order->save();

                SendOrderGotMail::dispatch(['order_id' => $this->order->id]);
                SendOrderGotSms::dispatch(['order_id' => $this->order->id]);
                SendOrderGotPush::dispatch(['order_id' => $this->order->id]);
                try {
                    event(new OrderConfirmed($this->order));
                } catch (Exception $exception) {

                    Log::info($exception->getMessage());
                }
            });
            return $this->order;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(Order $order, $auth = false): Order|array
    {
        try {
            if ($auth) {
                if ($order->user_id == Auth::user()->id) {
                    return $order;
                } else {
                    return [];
                }
            } else {
                return $order;
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function orderDetails(User $user, Order $order): Order|array
    {
        try {
            if ($order->user_id == $user->id) {
                return $order->load('transaction', 'orderItems', 'branch', 'user');
            } else {
                return [];
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function deliveryBoyOrderDetails(Order $order): Order|array
    {
        try {
            if ($order->delivery_boy_id == Auth::user()->id) {
                return $order;
            } else {
                return [];
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function deliveryBoyDeliveredOrderDetails(User $user, Order $order): Order|array
    {
        try {
            if ($order->delivery_boy_id == $user->id) {
                return $order;
            } else {
                return [];
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function deliveryBoyOrderCount(): array
    {
        try {
            $order                              = new Order;
            $orderCountArray                    = [];
            $orderCountArray['total_delivered'] = $order->where(
                ['delivery_boy_id' => Auth::user()->id, 'status' => OrderStatus::DELIVERED]
            )->count();
            $orderCountArray['total_returned']  = $order->where(
                ['delivery_boy_id' => Auth::user()->id, 'status' => OrderStatus::RETURNED]
            )->count();

            return $orderCountArray;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function deliveryBoyOrderChangeStatus(Order $order, OrderStatusRequest $request): Order
    {
        try {
            $transaction = Transaction::where('order_id', $order->id)->first();

            if (!$transaction && $order->payment_status == PaymentStatus::UNPAID) {
                $order->payment_status = PaymentStatus::PAID;
            }
            SendOrderMail::dispatch(['order_id' => $order->id, 'status' => OrderStatus::DELIVERED]);
            SendOrderSms::dispatch(['order_id' => $order->id, 'status' => OrderStatus::DELIVERED]);
            SendOrderPush::dispatch(['order_id' => $order->id, 'status' => OrderStatus::DELIVERED]);
            $order->status = OrderStatus::DELIVERED;
            $order->save();
            return $order;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function changeStatus(Order $order, $auth = false, OrderStatusRequest $request): Order|array
    {
        try {
            if ($auth) {
                if ($order->user_id == Auth::user()->id) {
                    if ($request->reason) {
                        $order->reason = $request->reason;
                    }

                    if ($request->status == OrderStatus::REJECTED || $request->status == OrderStatus::CANCELED) {
                        if ($order->transaction) {
                            app(PaymentService::class)->cashBack(
                                $order,
                                'credit',
                                rand(111111111111111, 99999999999999)
                            );
                        }
                    }
                    SendOrderMail::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                    SendOrderSms::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                    SendOrderPush::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                    $order->status = $request->status;
                    $order->save();
                }
            } else {
                if ($request->status == OrderStatus::REJECTED || $request->status == OrderStatus::CANCELED) {
                    $request->validate([
                        'reason' => 'required|max:700',
                    ]);

                    if ($request->reason) {
                        $order->reason = $request->reason;
                    }

                    if ($order->transaction) {
                        app(PaymentService::class)->cashBack(
                            $order,
                            'credit',
                            rand(111111111111111, 99999999999999)
                        );
                    }
                }
                SendOrderMail::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                SendOrderSms::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                SendOrderPush::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                $order->status = $request->status;
                $order->save();
                try {
                    if ($request->status == OrderStatus::ACCEPT) {
                        event(new OrderConfirmed($this->order));
                    }
                } catch (Exception $exception) {

                    Log::info($exception->getMessage());
                }
            }
            return $order;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function changePaymentStatus(Order $order, $auth = false, PaymentStatusRequest $request): Order|array
    {
        try {
            if ($auth) {
                if ($order->user_id == Auth::user()->id) {
                    $order->payment_status = $request->payment_status;
                    $order->save();
                    return $order;
                } else {
                    return [];
                }
            } else {
                $order->payment_status = $request->payment_status;
                $order->save();
                return $order;
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }


    public function tokenCreate(Order $order, $auth = false, TableOrderTokenRequest $request): Order|array
    {
        try {
            if ($auth) {
                if ($order->user_id == Auth::user()->id) {
                    $order->token = $request->token;
                    $order->save();
                    return $order;
                } else {
                    return [];
                }
            } else {
                $order->token = $request->token;
                $order->save();
                return $order;
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function selectDeliveryBoy(Order $order, $auth = false, Request $request): Order|array
    {
        try {
            if ($auth) {
                if ($order->user_id == Auth::user()->id) {
                    $order->delivery_boy_id = $request->delivery_boy_id;
                    $order->save();
                    SendOrderDeliveryBoyMail::dispatch(['order_id' => $order->id, 'status' => 101]);
                    SendOrderDeliveryBoySms::dispatch(['order_id' => $order->id, 'status' => 101]);
                    SendOrderDeliveryBoyPush::dispatch(['order_id' => $order->id, 'status' => 101]);
                    return $order;
                } else {
                    return [];
                }
            } else {
                $order->delivery_boy_id = $request->delivery_boy_id;
                $order->save();
                SendOrderDeliveryBoyMail::dispatch(['order_id' => $order->id, 'status' => 101]);
                SendOrderDeliveryBoySms::dispatch(['order_id' => $order->id, 'status' => 101]);
                SendOrderDeliveryBoyPush::dispatch(['order_id' => $order->id, 'status' => 101]);
                return $order;
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Order $order)
    {
        try {
            DB::transaction(function () use ($order) {
                $order->address()?->delete();
                $order->coupon()?->delete();
                $order->orderItems()?->delete();
                $order->Reservations()?->delete();
                $order->delete();
            });
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }

    public function salesReportOverview(Request $request)
    {
        try {
            $requests    = $request->all();
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_by') ?? 'desc';

            $orders = Order::with('transaction', 'orderItems')->where(function ($query) use ($requests) {
                if (isset($requests['from_date']) && isset($requests['to_date'])) {
                    $first_date = Date('Y-m-d', strtotime($requests['from_date']));
                    $last_date  = Date('Y-m-d', strtotime($requests['to_date']));
                    $query->whereDate('order_datetime', '>=', $first_date)->whereDate(
                        'order_datetime',
                        '<=',
                        $last_date
                    );
                }
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->orderFilter)) {
                        if ($key === "status") {
                            $query->where($key, (int)$request);
                        } else if ($key === 'payment_method' && (int)$request < 0) {
                            $query->where('pos_payment_method', abs($request));
                        } else if ($key === 'source') {
                            $query->where($key, $request);
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
            $salesReportArray = [];

            $salesReportArray['total_orders'] = $orders->count();

            $totalCashCard = AppLibrary::currencyAmountFormat($orders->where('pos_payment_method', 2)->sum('pos_received_amount'));

            $salesReportArray['total_cash'] = AppLibrary::currencyAmountFormat($orders->where('pos_payment_method', 1)->sum('total'));
            if (!empty($totalCashCard) && $totalCashCard > 0) {
                $salesReportArray['total_cash'] =  $salesReportArray['total_cash'] + $totalCashCard;
            }

            $salesReportArray['total_card'] = AppLibrary::currencyAmountFormat($orders->where('pos_payment_method', 2)->sum('total'));
             if (!empty($totalCashCard) && $totalCashCard > 0) {
             $salesReportArray['total_card'] =  $salesReportArray['total_card'] - $totalCashCard;
            }
            $salesReportArray['total_MOBILE_BANKING'] = AppLibrary::currencyAmountFormat($orders->where('pos_payment_method', 3)->sum('total'));
            $sumother = $orders->where('pos_payment_method', 4)->sum('total');

            $sumother = $sumother +  $orders->WhereNull('pos_payment_method')->sum('total');

            $salesReportArray['total_other'] = AppLibrary::currencyAmountFormat($sumother);
            $salesReportArray['total_mada'] = AppLibrary::currencyAmountFormat($orders->where('pos_payment_method', 5)->sum('total'));
            $salesReportArray['total_visa'] = AppLibrary::currencyAmountFormat($orders->where('pos_payment_method', 6)->sum('total'));

            $salesReportArray['total_earnings'] = AppLibrary::currencyAmountFormat($orders->sum('total'));
            $salesReportArray['total_discounts'] = AppLibrary::currencyAmountFormat($orders->sum('discount'));
            $salesReportArray['total_delivery_charges'] = AppLibrary::currencyAmountFormat($orders->sum('delivery_charge'));

            return $salesReportArray;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
