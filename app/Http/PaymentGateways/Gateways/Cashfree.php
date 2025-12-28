<?php

namespace App\Http\PaymentGateways\Gateways;


use App\Models\Currency;
use Exception;
use App\Enums\Activity;
use App\Enums\GatewayMode;
use App\Models\PaymentGateway;
use App\Services\PaymentService;
use App\Services\PaymentAbstract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ThemeSetting;
use Smartisan\Settings\Facades\Settings;
use Cashfree\Cashfree as CashfreeClient;
use Cashfree\Model\CreateOrderRequest;
use Cashfree\Model\CustomerDetails;
use Cashfree\Model\OrderMeta;



class Cashfree extends PaymentAbstract
{

    /**
     * @throws \Exception
     */

    public string $mode = "";
    public function __construct()
    {
        $paymentService = new PaymentService();
        parent::__construct($paymentService);
        $this->paymentGateway = PaymentGateway::with('gatewayOptions')->where(['slug' => 'cashfree'])->first();
        if (!blank($this->paymentGateway)) {
            $this->paymentGatewayOption = $this->paymentGateway->gatewayOptions->pluck('value', 'option');
            CashfreeClient::$XClientId = $this->paymentGatewayOption['cashfree_app_id'];
            CashfreeClient::$XClientSecret = $this->paymentGatewayOption['cashfree_secret_key'];
            CashfreeClient::$XEnvironment = $this->paymentGatewayOption['cashfree_mode'] == GatewayMode::SANDBOX ? CashfreeClient::$SANDBOX : CashfreeClient::$PRODUCTION;
            $this->mode =  $this->paymentGatewayOption['cashfree_mode'] == GatewayMode::SANDBOX ? 'sandbox' : "production";
        }
    }

    public function payment($order, $request): \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View
    {
        try {

            $currencyCode = 'INR';
            $currencyId   = Settings::group('site')->get('site_default_currency');
            if (!blank($currencyId)) {
                $currency = Currency::find($currencyId);
                if ($currency) {
                    $currencyCode = $currency->code;
                }
            }


            $cashfree = new CashfreeClient();
            $x_api_version = "2022-09-01";

            $create_orders_request = new CreateOrderRequest();
            $customer_details = new CustomerDetails();
            $order_meta = new OrderMeta();

            $customer_details->setCustomerId("customer" . $order->user?->id);
            $customer_details->setCustomerName($order->user?->name);
            $customer_details->setCustomerPhone($order->user?->phone);
            $customer_details->setCustomerEmail($order->user?->email);

            $create_orders_request->setCustomerDetails($customer_details);
            $create_orders_request->setOrderAmount(round($order->total));
            $create_orders_request->setOrderCurrency($currencyCode);

            $order_meta->setReturnUrl(route('payment.success', ['order' => $order, 'paymentGateway' => 'cashfree']));
            $order_meta->setNotifyUrl(route('payment.fail', ['order' => $order, 'paymentGateway' => 'cashfree']));


            $order_meta->setPaymentMethods('cc,dc,upi,nb,ccc,ppc,paypal,emi');
            $create_orders_request->setOrderMeta($order_meta);


            $result = $cashfree->pGCreateOrder($x_api_version, $create_orders_request, null, null, null);
            $res = json_decode($result[0]);

            if (isset($res->payment_session_id)) {
                $paymentSessionId   = $res->payment_session_id;
                $cashfreePayLink    = route('payment.success', ['order' => $order, 'paymentGateway' => 'cashfree', 'request' => $res]);
                $cashfreeCancelLink = route('payment.fail', ['order' => $order, 'paymentGateway' => 'cashfree']);
                $mode               = $this->mode;

                $company     = Settings::group('company')->all();
                $logo        = ThemeSetting::where(['key' => 'theme_logo'])->first();
                $faviconLogo = ThemeSetting::where(['key' => 'theme_favicon_logo'])->first();


                return view(
                    'paymentGateways.cashfree.cashfreeJs',
                    compact('paymentSessionId', 'cashfreePayLink', 'cashfreeCancelLink', 'mode', 'company', 'logo', 'faviconLogo', 'order')
                );
            } else {
                return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'cashfree'])->with(
                    'error',
                    trans('all.message.something_wrong')
                );
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'cashfree'])->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function status(): bool
    {
        $paymentGateways = PaymentGateway::where(['slug' => 'cashfree', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }

    public function success($order, $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $cashfree = new CashfreeClient();
            $x_api_version = "2022-09-01";
            $order_id = $request['request']['order_id'];
            $result = $cashfree->PGOrderFetchPayments($x_api_version, $order_id, null, null, null);
            if ($result[1] == 200 && $result[0][0]['payment_status'] == 'SUCCESS') {
                $this->paymentService->payment($order, 'cashfree', $result[0][0]['cf_payment_id']);
                return redirect()->route('payment.successful', ['order' => $order])->with('success', trans('all.message.payment_successful'));
            } else {
                return redirect()->route('payment.fail', [
                    'order'          => $order,
                    'paymentGateway' => 'cashfree'
                ])->with('error', trans('all.message.something_wrong'));
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return redirect()->route('payment.fail', ['order' => $order, 'paymentGateway' => 'cashfree'])->with(
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