<?php

namespace App\Http\SmsGateways\Gateways;


use App\Enums\Activity;
use App\Models\SmsGateway;
use App\Services\SmsAbstract;
use Exception;
use Illuminate\Support\Facades\Log;

class Msg91 extends SmsAbstract
{

    public string $msg91_key;
    public string $msg91_sender_id;
    public string $msg91_template_id;
    public string $msg91_template_variable;


    public function __construct()
    {
        parent::__construct();

        $this->smsGateway = SmsGateway::with('gatewayOptions')->where(['slug' => 'msg91'])->first();
        if (!blank($this->smsGateway)) {
            $this->smsGatewayOption = $this->smsGateway->gatewayOptions->pluck('value', 'option');
            $this->msg91_key = $this->smsGatewayOption['msg91_key'];
            $this->msg91_sender_id = $this->smsGatewayOption['msg91_sender_id'];
            $this->msg91_template_id = $this->smsGatewayOption['msg91_template_id'];
            $this->msg91_template_variable = $this->smsGatewayOption['msg91_template_variable'];
        }
    }

    public function status(): bool
    {
        $paymentGateways = SmsGateway::where(['slug' => 'msg91', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }

    public function send($code, $phone, $message): void
    {
        try {

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://control.msg91.com/api/v5/flow/",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode([
                    'template_id' => $this->msg91_template_id,
                    'short_url' => '0',
                    'recipients' => [
                        [
                            'mobiles' => trim($code, '+') . $phone,
                            $this->msg91_template_variable => $message
                        ]
                    ]
                ]),
                CURLOPT_HTTPHEADER => [
                    "accept: application/json",
                    "authkey: " . $this->msg91_key,
                    "content-type: application/json"
                ],
            ]);

            $response = curl_exec($curl);
            curl_close($curl);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
        }
    }
}