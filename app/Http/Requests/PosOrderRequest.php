<?php

namespace App\Http\Requests;

use App\Enums\Activity;
use App\Enums\OrderType;
use App\Enums\PosPaymentMethod;
use App\Rules\ValidJsonOrder;
use Smartisan\Settings\Facades\Settings;
use Illuminate\Foundation\Http\FormRequest;

class PosOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'token'           => request('order_type') !== OrderType::DINING_TABLE ?
                ['required', 'numeric'] :  ['nullable'],
            'customer_id'     => ['required', 'numeric'],
            'branch_id'       => ['required', 'numeric'],
            'subtotal'        => ['required', 'numeric'],
            'discount'        => ['nullable', 'numeric'],
            'dining_table_id' => request('order_type') === OrderType::DINING_TABLE ? [
                'required',
                'numeric'
            ] : ['nullable'],
            'delivery_charge' => request('order_type') === OrderType::DELIVERY ? [
                'required',
                'numeric'
            ] : ['nullable'],
            'total'            => ['required', 'numeric'],
            'order_type'       => ['required', 'numeric'],
            'is_advance_order' => ['required', 'numeric'],
            'address_id'       => request('order_type') === OrderType::DELIVERY ? [
                'required',
                'numeric'
            ] : ['nullable'],
            'delivery_time'    => request('order_type') === OrderType::DELIVERY ? [
                'nullable',
                'string'
            ] : ['nullable'],
            'coupon_id'           => ['nullable', 'numeric'],
            'source'              => ['required', 'numeric'],
            'items'               => ['required', 'json', new ValidJsonOrder],
            'pos_payment_method'  => ['required', 'numeric'],
            'pos_payment_note'    => request('pos_payment_method') === PosPaymentMethod::CARD || request('pos_payment_method') === PosPaymentMethod::MOBILE_BANKING || request('pos_payment_method') === PosPaymentMethod::OTHER ?  ['nullable', 'string'] : ['nullable', 'string'],
            'pos_received_amount' => request('pos_payment_method') === PosPaymentMethod::CASH ? ['required', 'numeric'] : ['nullable', 'numeric'],
            'Reservations '       =>  ['nullable'],
            'NetworkAmount' => request('pos_payment_method') === PosPaymentMethod::CARD ? ['required', 'numeric'] : ['nullable', 'numeric'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (request('order_type') == OrderType::DELIVERY && Settings::group('order_setup')->get("order_setup_delivery") == Activity::DISABLE) {
                $validator->errors()->add('order_type', 'This order type is disabled now you can try another order type right now or call the management.');
            } else if (request('order_type') == OrderType::TAKEAWAY && Settings::group('order_setup')->get("order_setup_takeaway") == Activity::DISABLE) {
                $validator->errors()->add('order_type', 'This order type is disabled now you can try another order type right now or call the management.');
            } else if (blank(request('order_type'))) {
                $validator->errors()->add('order_type', 'This order type is disabled now you can try another order type right now or call the management.');
            }
            if (request('pos_payment_method') == PosPaymentMethod::CASH && ((float)request('total') > (float)request('pos_received_amount'))) {
                $validator->errors()->add('pos_received_amount', 'لا بمكن ان يكون المبلغ المستلم اقل من مبلغ الفاتورة.');
            }
            if (request('pos_payment_method') == PosPaymentMethod::CARD && ((float)request('total') > (float)request('pos_received_amount') + (float)request('NetworkAmount'))) {
                $validator->errors()->add('pos_received_amount', 'لا بمكن ان يكون المبلغ المستلم اقل من مبلغ الفاتورة.');
            }
            if (request('pos_payment_method') == PosPaymentMethod::CARD && ((float)request('total') < (float)request('pos_received_amount') + (float)request('NetworkAmount'))) {
                $validator->errors()->add('pos_received_amount', 'لا بمكن ان يكون المبلغ المستلم اكبر من مبلغ الفاتورة.');
            }
        });
    }

    public function messages()
    {
        return [
            'pos_payment_note.required'    => request('pos_payment_method') == PosPaymentMethod::CARD ? 'Last 4 digits of card is required' : (request('pos_payment_method') == PosPaymentMethod::MOBILE_BANKING ? 'Transaction ID field is required' : 'Payment note field is required'),
            'pos_payment_note.min_digits'  => 'The cart must contain at least 0 digits',
            'pos_payment_note.max_digits'  => 'The cart must not contain more than 4 digits',
            'pos_received_amount.required' => 'The received amount field is required',
            'dining_table_id.required'     => 'The dining table field is required',
            'NetworkAmount.required'     => request('pos_payment_method') == PosPaymentMethod::CARD ? 'The network amount field is required':'',
        ];
    }
}
