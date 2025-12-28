<?php

namespace App\Http\PaymentGateways\Gateways;


use Exception;
use App\Enums\Activity;
use App\Enums\GatewayMode;
use App\Models\PaymentGateway;
use App\Models\Currency;
use App\Services\PaymentService;
use App\Services\PaymentAbstract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Smartisan\Settings\Facades\Settings;


class Telr extends PaymentAbstract
{
    public string $baseUrl;
    public string $description;

    public function __construct()
    {
        $paymentService = new PaymentService();
        parent::__construct($paymentService);
        $this->paymentGateway = PaymentGateway::with('gatewayOptions')->where(['slug' => 'telr'])->first();
        $this->paymentGatewayOption = $this->paymentGateway->gatewayOptions->pluck('value', 'option');
        $this->baseUrl = "https://secure.telr.com/gateway/order.json";
        $this->description = "Food Payment";
    }

    public function payment($order, $request)
    {
        try {
            $currencyCode = 'AED';
            $currencyId = Settings::group('site')->get('site_default_currency');
            if (!blank($currencyId)) {
                $currency = Currency::find($currencyId);
                if ($currency) {
                    $currencyCode = $currency->code;
                }
            }

            $mode = $this->paymentGatewayOption['telr_mode'] == GatewayMode::SANDBOX ? 1 : 0;

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $this->baseUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode([
                    'method' => 'create',
                    'store' => $this->paymentGatewayOption['telr_store_id'],
                    'authkey' => $this->paymentGatewayOption['telr_store_auth_key'],
                    'framed' => 0,
                    'order' => [
                        'cartid' => $order->order_serial_no,
                        'test' => $mode,
                        'amount' => $order->total,
                        'currency' => $currencyCode,
                        'description' => $this->description
                    ],
                    'return' => [
                        'authorised' => route('payment.success', ['order' => $order, 'paymentGateway' => 'telr']),
                        'declined' => route('payment.cancel', ['order' => $order, 'paymentGateway' => 'telr']),
                        'cancelled' => route('payment.cancel', ['order' => $order, 'paymentGateway' => 'telr'])
                    ]
                ]),
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/json",
                    "accept: application/json"
                ],
            ]);

            $response = curl_exec($curl);
            $err      = curl_error($curl);
            curl_close($curl);

            if ($err) {
                return redirect()->route('payment.fail', [
                    'order' => $order,
                    'paymentGateway' => 'telr'
                ]);
            } else {
                $res = json_decode($response);
                if (isset($res->order->url) && $res->order->url !== "") {
                    return redirect($res->order->url);
                } else {
                    return redirect()->route('payment.index', [
                        'order' => $order,
                        'paymentGateway' => 'telr'
                    ])->with('error', "JSON Data parsing error!");
                }
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('payment.index', [
                'order' => $order,
                'paymentGateway' => 'telr'
            ])->with('error', $e->getMessage());
        }
    }

    public function status(): bool
    {
        $paymentGateways = PaymentGateway::where(['slug' => 'telr', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }

    public function success($order, $request)
    {
        try {
            if (isset($request->PAYID)) {
                $paymentService = new PaymentService;
                $paymentService->payment($order, 'telr', $request->PAYID);
                return redirect()->route('payment.successful', ['order' => $order])->with('success', trans('all.message.payment_successful'));
            } else {
                return redirect()->route('payment.fail', [
                    'order' => $order,
                    'paymentGateway' => 'telr'
                ])->with('error', trans('all.message.something_wrong'));
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return redirect()->route('payment.fail', [
                'order' => $order,
                'paymentGateway' => 'telr'
            ])->with('error', $e->getMessage());
        }
    }

    public function fail($order, $request): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('payment.index', ['order' => $order])->with('error', trans('all.message.something_wrong'));
    }

    public function cancel($order, $request): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('home')->with('error', trans('all.message.payment_canceled'));
    }
}
