<?php

namespace App\Http\Resources;

use App\Enums\Ask;
use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use Exception;
use Illuminate\Support\Facades\Log;
use Smartisan\Settings\Facades\Settings;
use App\Http\Controllers\Admin\QRController;

class OrderDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'                                  => $this->id,
            'order_serial_no'                     => $this->order_serial_no,
            'token'                               => $this->token,
            "subtotal_currency_price"             => AppLibrary::currencyAmountFormat($this->subtotal),
            "subtotal_without_tax_currency_price" => AppLibrary::currencyAmountFormat($this->subtotal - $this->total_tax),
            "discount_currency_price"             => AppLibrary::currencyAmountFormat($this->discount),
            "delivery_charge_currency_price"      => AppLibrary::currencyAmountFormat($this->delivery_charge),
            "total_currency_price"                => AppLibrary::currencyAmountFormat($this->total),
            "total_tax_currency_price"            => AppLibrary::currencyAmountFormat($this->total_tax),
            'order_type'                          => $this->order_type,
            'order_datetime'                      => AppLibrary::datetime($this->order_datetime),
            'order_date'                          => AppLibrary::date($this->order_datetime),
            'order_time'                          => AppLibrary::time($this->order_datetime),
            'delivery_date'                       => $this->is_advance_order == Ask::YES ? AppLibrary::increaseDate($this->order_datetime, 1) : AppLibrary::date($this->order_datetime),
            'delivery_time'                       => AppLibrary::deliveryTime($this->delivery_time),
            'payment_method'                      => $this->payment_method,
            'payment_status'                      => $this->payment_status,
            'is_advance_order'                    => $this->is_advance_order,
            'preparation_time'                    => $this->preparation_time,
            'status'                              => $this->status,
            'status_name'                         => trans('orderStatus.' . $this->status),
            'reason'                              => $this->reason,
            'user'                                => new OrderUserResource($this->user?->load('roles', 'media')),
            'order_address'                       => new AddressResource($this->address),
            'branch'                              => new BranchResource($this->branch),
            'delivery_boy'                        => new OrderUserResource($this->deliveryBoy?->load('roles', 'media')),
            'coupon'                              => new CouponResource($this->coupon),
            'transaction'                         => new TransactionResource($this->transaction),
            'order_items'                         => OrderItemResource::collection($this->orderItems->load('orderItem')),
            'table_name'                          => $this->diningTable?->name,
            'pos_payment_method'                  => $this->pos_payment_method,
            'pos_payment_note'                    => $this->pos_payment_note,
            'source'                              => $this->source,
            'pos_received_amount'                 => $this->pos_received_amount,
            'pos_received_currency_amount'        => AppLibrary::currencyAmountFormat($this->pos_received_amount),
            'cash_back_amount'                    => $this->pos_received_amount - $this->total,
            'cash_back_currency_amount'           => AppLibrary::currencyAmountFormat($this->pos_received_amount - $this->total),
            'QrCode' =>  $this->get_QrCode($request),
            'order_Reservations'                       => new OrderReservationsResource($this->Reservations),
            'NetworkAmount' => AppLibrary::currencyAmountFormat($this->NetworkAmount),
        ];
    }
    public function get_QrCode($request)
    {
        try {
            //الرقم الضريبي=البائع-اجمالي الضريبة-اجمالي الفاتورة-التاريخ
            $total_tax =   AppLibrary::currencyAmountFormat($this->total_tax);
            $total = AppLibrary::currencyAmountFormat($this->total);
            $order_datetime =    AppLibrary::datetime($this->order_datetime, 'Y-m-d H:i:s');
            $company = Settings::group('company')->all();
            $company_name = $company['company_name'];
            $vat_number = $company['vat_number'];
            $order_serial_no = $this->order_serial_no;
            $qrContent = [
                'seller_name' => $company_name,
                'vat_number' => $vat_number,
                'invoice_date' =>   $order_datetime,
                'total_amount' => $total,
                'vat_amount' =>  $total_tax,
            ];
            $QRController = new QRController();
            $QRcode = $QRController->generate($qrContent, false);
            Log::info($QRcode);
            return $QRcode;
            // $qrContent = json_encode([
            //     'Sellers Name' => 'ghj jhk lkee sdee',
            //     'Sellers TRN' => $company_zip_code,
            //     'Invoice DateTime' => $this->order_datetime,
            //     'Invoice Total (with VAT)' => $total,
            //     'VAT Total' => $total_tax,
            // ]);
            // Log::info($qrContent);
            // $qrCode = (QrCode::format('png')->size(pixels: 200)->generate($qrContent));
            // Log::info($qrCode);
            // $QRcode = $this->decode_base64('data:image/png;base64,' . $qrCode);
            // Log::info($QRcode);
            // return  $QRcode;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
