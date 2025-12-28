<?php

namespace App\Http\PaymentGateways\Gateways;

use Exception;
use Iyzipay\Options;
use App\Enums\Activity;
use App\Models\Currency;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\Locale;
use App\Enums\GatewayMode;
use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;
use App\Models\PaymentGateway;
use Iyzipay\Model\PaymentGroup;
use App\Services\PaymentService;
use Iyzipay\Model\PayWithIyzico;
use App\Services\PaymentAbstract;
use Iyzipay\Model\BasketItemType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Smartisan\Settings\Facades\Settings;
use Iyzipay\Model\PayWithIyzicoInitialize;
use Iyzipay\Model\Currency as IyzipayCurrency;
use Iyzipay\Request\RetrievePayWithIyzicoRequest;


class Iyzico extends PaymentAbstract
{

    public bool $response = false;
    public string $baseUrl;
    public $options;

    public function __construct()
    {
        $paymentService = new PaymentService();
        parent::__construct($paymentService);

        $this->paymentGateway = PaymentGateway::with('gatewayOptions')->where(['slug' => 'iyzico'])->first();
        if (!blank($this->paymentGateway)) {
            $this->paymentGatewayOption = $this->paymentGateway->gatewayOptions->pluck('value', 'option');
            $this->baseUrl           = $this->paymentGatewayOption['iyzico_mode'] == GatewayMode::SANDBOX ? 'https://sandbox-api.iyzipay.com'
                : 'https://api.iyzipay.com';

            $this->options = new Options();
            $this->options->setApiKey($this->paymentGatewayOption['iyzico_api_key']);
            $this->options->setSecretKey($this->paymentGatewayOption['iyzico_secret_key']);
            $this->options->setBaseUrl($this->baseUrl);
        }
    }

    public function payment($order, $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $company = Settings::group('company')->all();

            $currencyCode = IyzipayCurrency::TL;
            $currencyId   = Settings::group('site')->get('site_default_currency');
            if (!blank($currencyId)) {
                $currency = Currency::find($currencyId);
                if ($currency) {
                    $currencyCode = $currency->code;
                }
            }

            $request = new \Iyzipay\Request\CreatePayWithIyzicoInitializeRequest();
            $request->setLocale(Locale::EN);
            $request->setConversationId($order->order_serial_no);
            $request->setPrice($order->subtotal);
            $request->setPaidPrice($order->total);
            $request->setCurrency($currencyCode);
            $request->setCallbackUrl(route('payment.success', ['order' => $order, 'paymentGateway' => 'iyzico']));
            $request->setBasketId($order->order_serial_no);
            $request->setPaymentGroup(PaymentGroup::PRODUCT);

            $buyer = new Buyer();
            $buyer->setId($order->order_serial_no);
            $buyer->setName($order->user->name);
            $buyer->setSurname($order->user->name);
            $buyer->setGsmNumber($order->user->phone);
            $buyer->setEmail($order->user->email);
            $buyer->setIdentityNumber($order->user->phone);
            $buyer->setLastLoginDate(date('Y-m-d h:i:s', strtotime($order->created_at)));
            $buyer->setRegistrationDate(date('Y-m-d h:i:s', strtotime($order->user?->created_at)));
            $buyer->setRegistrationAddress($order->address?->address);
            $buyer->setIp("12345678");
            $buyer->setCity($company['company_state']);
            $buyer->setCountry("Turkey");
            $buyer->setZipCode($company['company_zip_code']);
            $request->setBuyer($buyer);

            $shippingAddress = new Address();
            $shippingAddress->setContactName($order->user->name);
            $shippingAddress->setCity($company['company_state']);
            $shippingAddress->setCountry("Turkey");
            $shippingAddress->setAddress($order->address?->address);
            $shippingAddress->setZipCode($company['company_zip_code']);
            $request->setShippingAddress($shippingAddress);

            $billingAddress = new Address();
            $billingAddress->setContactName($order->user?->name);
            $billingAddress->setCity($company['company_state']);
            $billingAddress->setCountry("Turkey");
            $billingAddress->setAddress($order->address?->address);
            $billingAddress->setZipCode($company['company_zip_code']);
            $request->setBillingAddress($billingAddress);

            $basketItems = array();
            $firstBasketItem = new BasketItem();
            $firstBasketItem->setId($order->order_serial_no);
            $firstBasketItem->setName("Food Items");
            $firstBasketItem->setCategory1("Food");
            $firstBasketItem->setItemType(BasketItemType::PHYSICAL);
            $firstBasketItem->setPrice($order->subtotal);
            $basketItems[0] = $firstBasketItem;
            $request->setBasketItems($basketItems);

            $payment = PayWithIyzicoInitialize::create($request, $this->options);

            if ($payment->getPayWithIyzicoPageUrl()) {
                return redirect()->away($payment->getPayWithIyzicoPageUrl());
            } else {
                return redirect()->route('payment.index', [
                    'order'          => $order,
                    'paymentGateway' => 'iyzico'
                ])->with('error', "JSON Data parsing error!");
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'iyzico'])->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function status(): bool
    {
        $paymentGateways = PaymentGateway::where(['slug' => 'iyzico', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }

    public function success($order, $request): \Illuminate\Http\RedirectResponse
    {
        try {
            if ($request['token']) {
                $token = new RetrievePayWithIyzicoRequest();
                $token->setToken($request['token']);
                $paymentResponse = PayWithIyzico::retrieve($token, $this->options);

                if ($paymentResponse->getPaymentStatus() == "SUCCESS") {
                    $paymentService = new PaymentService;
                    $paymentService->payment($order, 'iyzico', $paymentResponse->getPaymentID());
                    return redirect()->route('payment.successful', ['order' => $order])->with('success', trans('all.message.payment_successful'));
                } else {
                    return redirect()->route('payment.fail', [
                        'order'          => $order,
                        'paymentGateway' => 'iyzico'
                    ])->with('error', $this->response['message'] ?? trans('all.message.something_wrong'));
                }
            } else {
                return redirect()->route('payment.fail', [
                    'order'          => $order,
                    'paymentGateway' => 'iyzico'
                ])->with('error', $this->response['message'] ?? trans('all.message.something_wrong'));
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return redirect()->route('payment.fail', [
                'order'          => $order,
                'paymentGateway' => 'iyzico'
            ])->with('error', $e->getMessage());
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