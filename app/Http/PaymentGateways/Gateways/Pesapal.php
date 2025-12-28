<?php

namespace App\Http\PaymentGateways\Gateways;


use Exception;
use App\Enums\Activity;
use App\Models\Currency;
use App\Enums\GatewayMode;
use Illuminate\Support\Str;
use App\Models\PaymentGateway;
use App\Services\PaymentService;
use App\Services\PaymentAbstract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Smartisan\Settings\Facades\Settings;
use Nyawach\LaravelPesapal\Facades\LaravelPesapal;

class Pesapal extends PaymentAbstract
{
    public mixed $response;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $paymentService = new PaymentService();
        parent::__construct($paymentService);
        $this->paymentGateway = PaymentGateway::with('gatewayOptions')->where(['slug' => 'pesapal'])->first();
        if (!blank($this->paymentGateway)) {
            $this->paymentGatewayOption = $this->paymentGateway->gatewayOptions->pluck('value', 'option');
            Config::set('pesapal.pesapal_env', $this->paymentGatewayOption['pesapal_mode']) == GatewayMode::SANDBOX ? "sandbox" : "production";
            Config::set('pesapal.consumer_key', $this->paymentGatewayOption['pesapal_consumer_key']);
            Config::set('pesapal.consumer_secret', $this->paymentGatewayOption['pesapal_consumer_secret']);
            Config::set('pesapal.pesapal_guard', Str::random(20));
            Config::set('pesapal.pesapal_ipn_id', $this->paymentGatewayOption['pesapal_ipn_id']);
        }
    }

    public function payment($order, $request)
    {
        try {
            $ipn = array();
            $ipn["url"] = url('/') . '/ipn/' . config('pesapal.pesapal_guard');
            $ipn["ipn_notification_type"] = 'get';
            $ipnId = LaravelPesapal::registerIpn($ipn);

            $currencyCode = 'KES';
            $currencyId = Settings::group('site')->get('site_default_currency');
            if (!blank($currencyId)) {
                $currency = Currency::find($currencyId);
                if ($currency) {
                    $currencyCode = $currency->code;
                }
            }

            $postData                                     = array();
            $postData["currency"]                         = $currencyCode;
            $postData["amount"]                           = $order->total;
            $postData["id"]                               = $order->order_serial_no;
            $postData["description"]                      = "Payment for order number " . $order->order_serial_no;
            $postData["billing_address"]["phone_number"]  = $order->user->phone;
            $postData["billing_address"]["email_address"] = $order->user->email;
            $postData["billing_address"]["first_name"]    = $order->user->name;
            $postData["callback_url"]                     = route('payment.success', ['order' => $order, 'paymentGateway' => 'pesapal']);
            $postData["notification_id"]                  = $ipnId->ipn_id;

            $response = LaravelPesapal::getMerchantOrderURL($postData);


            if (isset($response->redirect_url)) {
                return redirect($response->redirect_url);
            } else {
                $message = isset($response?->error?->message) ? $response?->error?->message : trans('all.message.something_wrong');
                return redirect()->route('payment.index', ['order' => $order,  'paymentGateway' => 'pesapal'])->with('error', $message);
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('payment.index', [
                'order' => $order,
                'paymentGateway' => 'pesapal'
            ])->with('error', $e->getMessage());
        }
    }

    public function status(): bool
    {
        $paymentGateways = PaymentGateway::with('gatewayOptions')->where(['slug' => 'pesapal', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }

    public function success($order, $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $transaction_status = LaravelPesapal::getTransactionStatus($request->OrderTrackingId);

            if ($transaction_status->status_code === 1) {
                if ($order->amount == $transaction_status->amount) {
                    $this->paymentService->payment($order, 'pesapal', $request->OrderTrackingId);
                    return redirect()->route('payment.successful', ['order' => $order])->with('success', trans('all.message.payment_successful'));
                } else {
                    return redirect()->route('payment.fail', [
                        'order' => $order,
                        'paymentGateway' => 'pesapal'
                    ])->with('error', trans('all.message.something_wrong'));
                }
            } else {
                return redirect()->route('payment.fail', [
                    'order' => $order,
                    'paymentGateway' => 'pesapal'
                ])->with('error', trans('all.message.transaction_failed'));
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return redirect()->route('payment.fail', [
                'order' => $order,
                'paymentGateway' => 'pesapal'
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