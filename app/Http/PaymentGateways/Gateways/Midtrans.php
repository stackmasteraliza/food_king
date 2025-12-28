<?php

namespace App\Http\PaymentGateways\Gateways;


use Exception;
use Midtrans\Snap;
use Midtrans\Config;
use App\Enums\Activity;
use App\Models\Currency;
use Midtrans\Transaction;
use App\Enums\GatewayMode;
use App\Models\PaymentGateway;
use App\Services\PaymentService;
use App\Services\PaymentAbstract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Smartisan\Settings\Facades\Settings;

class Midtrans extends PaymentAbstract
{

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $paymentService = new PaymentService();
        parent::__construct($paymentService);
        $this->paymentGateway = PaymentGateway::with('gatewayOptions')->where(['slug' => 'midtrans'])->first();
        if (!blank($this->paymentGateway)) {
            $this->paymentGatewayOption = $this->paymentGateway->gatewayOptions->pluck('value', 'option');
            Config::$serverKey = $this->paymentGatewayOption['midtrans_server_key'];
            Config::$clientKey = $this->paymentGatewayOption['midtrans_client_key'];
            Config::$isProduction = $this->paymentGatewayOption['midtrans_mode'] == GatewayMode::SANDBOX ? false : true;
            Config::$isSanitized = true;
            Config::$is3ds = true;
        }
    }

    public function payment($order, $request): \Illuminate\Http\RedirectResponse
    {
        try {

            $currencyCode = 'IDR';
            $currencyId   = Settings::group('site')->get('site_default_currency');
            if (!blank($currencyId)) {
                $currency = Currency::find($currencyId);
                if ($currency) {
                    $currencyCode = $currency->code;
                }
            }

            if (env('DEMO')) {
                $currencyCode = 'IDR';
            }

            if (!in_array($currencyCode, ["IDR", "SGD"])) {
                return redirect()->route('payment.index', [
                    'order' => $order,
                    'paymentGateway' => 'midtrans'
                ])->with('error', "Currency is not supported . Use IDR or SGD");
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_serial_no . rand(0, 999),
                    'gross_amount' => $order->total,
                    'currency' => $currencyCode
                ],
                'customer_details' => [
                    'first_name' => $order->user?->FirstName,
                    'last_name' => $order->user?->LastName,
                    'email' => $order->user?->email,
                    'phone' => $order->user?->phone,
                ],
                'callbacks' => [
                    'finish' => route('payment.success', ['order' => $order, 'paymentGateway' => 'midtrans']),
                    'notification' => route('payment.success', ['order' => $order, 'paymentGateway' => 'midtrans'])
                ],
            ];

            $paymentUrl = Snap::createTransaction($params)->redirect_url;

            if (isset($paymentUrl)) {
                return redirect()->away($paymentUrl);
            } else {
                return redirect()->route('payment.fail', ['order' => $order, 'paymentGateway' => 'midtrans'])->with(
                    'error',
                    trans('all.message.something_wrong')
                );
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'midtrans'])->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function status(): bool
    {
        $paymentGateways = PaymentGateway::where(['slug' => 'midtrans', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }

    public function success($order, $request): \Illuminate\Http\RedirectResponse
    {
        try {

            $transaction = (array) Transaction::status($request['order_id']);
            if (
                isset($transaction['status_code']) && $transaction['status_code'] == "200" && isset($request['transaction_status']) &&
                ($request['transaction_status'] == 'capture' || $request['transaction_status'] == 'settlement')
            ) {
                $this->paymentService->payment($order, 'midtrans', $transaction['transaction_id']);
                return redirect()->route('payment.successful', ['order' => $order])->with(
                    'success',
                    trans('all.message.payment_successful')
                );
            } else {
                return redirect()->route('payment.fail', ['order' => $order, 'paymentGateway' => 'midtrans'])->with(
                    'error',
                    trans('all.message.something_wrong')
                );
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return redirect()->route('payment.fail', ['order' => $order, 'paymentGateway' => 'midtrans'])->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function fail($order, $request): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('payment.index', ['order' => $order])->with(
            'error',
            trans('all.message.something_wrong')
        );
    }

    public function cancel($order, $request): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('home')->with('error', trans('all.message.payment_canceled'));
    }
}