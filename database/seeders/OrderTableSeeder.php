<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\PosPaymentMethod;
use App\Enums\Source;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;
use App\Models\Order;

class OrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $envService = new EnvEditor();
        if ($envService->getValue('DEMO')) {
            Order::insert([
                [
                    'order_serial_no'  => date('dmy') . '1',
                    'token'            => null,
                    'user_id'          => 3,
                    'branch_id'        => 1,
                    'subtotal'         => 18.730000,
                    'discount'         => 0.000000,
                    'delivery_charge'  => 5.364328,
                    'total_tax'        => 0.425000,
                    'total'            => 24.090000,
                    'order_type'       => OrderType::DELIVERY,
                    'pos_payment_method' => null,
                    'order_datetime'   => now(),
                    'delivery_time'    => '20:30 - 21:00',
                    'preparation_time' => 30,
                    'is_advance_order' => 10,
                    'payment_method'   => 1,
                    'payment_status'   => 5,
                    'status'           => OrderStatus::DELIVERED,
                    'dining_table_id'  => null,
                    'delivery_boy_id'  => 4,
                    'reason'           => null,
                    'source'           => Source::WEB,
                    'created_at'       => now(),
                    'updated_at'       => now()
                ],
                [
                    'order_serial_no'  => date('dmy') . '2',
                    'token'            => null,
                    'user_id'          => 3,
                    'branch_id'        => 1,
                    'subtotal'         => 23.020000,
                    'discount'         => 5.000000,
                    'delivery_charge'  => 5.364328,
                    'total_tax'        => 4.000000,
                    'total'            => 23.380000,
                    'order_type'       => OrderType::DELIVERY,
                    'pos_payment_method' => null,
                    'order_datetime'   => now(),
                    'delivery_time'    => '20:30 - 21:00',
                    'preparation_time' => 30,
                    'is_advance_order' => 10,
                    'payment_method'   => 3,
                    'payment_status'   => 5,
                    'status'           => OrderStatus::DELIVERED,
                    'dining_table_id'  => null,
                    'delivery_boy_id'  => 4,
                    'reason'           => null,
                    'source'           => Source::WEB,
                    'created_at'       => now(),
                    'updated_at'       => now()
                ],
                [
                    'order_serial_no'  => date('dmy') . '3',
                    'token'            => null,
                    'user_id'          => 3,
                    'branch_id'        => 1,
                    'subtotal'         => 8.500000,
                    'discount'         => 0.000000,
                    'delivery_charge'  => 0.364328,
                    'total_tax'        => 0.000000,
                    'total'            => 8.500000,
                    'order_type'       => OrderType::TAKEAWAY,
                    'pos_payment_method' => null,
                    'order_datetime'   => now(),
                    'delivery_time'    => '20:30 - 21:00',
                    'preparation_time' => 30,
                    'is_advance_order' => 10,
                    'payment_method'   => 1,
                    'payment_status'   => 5,
                    'status'           => OrderStatus::DELIVERED,
                    'dining_table_id'  => null,
                    'delivery_boy_id'  => null,
                    'reason'           => null,
                    'source'           => Source::WEB,
                    'created_at'       => now(),
                    'updated_at'       => now()
                ],
                [
                    'order_serial_no'  => date('dmy') . '4',
                    'token'            => null,
                    'user_id'          => 3,
                    'branch_id'        => 1,
                    'subtotal'         => 5.500000,
                    'discount'         => 0.000000,
                    'delivery_charge'  => 1.000000,
                    'total_tax'        => 0.000000,
                    'total'            => 6.500000,
                    'order_type'       => OrderType::DELIVERY,
                    'pos_payment_method' => null,
                    'order_datetime'   => now(),
                    'delivery_time'    => '20:30 - 21:00',
                    'preparation_time' => 30,
                    'is_advance_order' => 10,
                    'payment_method'   => 1,
                    'payment_status'   => 10,
                    'status'           => OrderStatus::OUT_FOR_DELIVERY,
                    'dining_table_id'  => null,
                    'delivery_boy_id'  => 4,
                    'reason'           => null,
                    'source'           => Source::WEB,
                    'created_at'       => now(),
                    'updated_at'       => now()
                ],
                [
                    'order_serial_no'    => date('dmy') . '5',
                    'token'              => "0101",
                    'user_id'            => 2,
                    'branch_id'          => 1,
                    'subtotal'           => 18.730000,
                    'discount'           => 0.000000,
                    'delivery_charge'    => 0.000000,
                    'total_tax'          => 0.425000,
                    'total'              => 18.730000,
                    'order_type'         => OrderType::TAKEAWAY,
                    'pos_payment_method' => PosPaymentMethod::CASH,
                    'order_datetime'     => now(),
                    'delivery_time'      => '20:30 - 21:00',
                    'preparation_time'   => 30,
                    'is_advance_order'   => 10,
                    'payment_method'     => 1,
                    'payment_status'     => 5,
                    'status'             => OrderStatus::DELIVERED,
                    'dining_table_id'    => null,
                    'delivery_boy_id'    => null,
                    'reason'             => null,
                    'source'             => Source::POS,
                    'created_at'         => now(),
                    'updated_at'         => now()
                ],
                [
                    'order_serial_no'    => date('dmy') . '6',
                    'token'              => "0102",
                    'user_id'            => 3,
                    'branch_id'          => 1,
                    'subtotal'           => 8.500000,
                    'discount'           => 0.000000,
                    'delivery_charge'    => 0.000000,
                    'total_tax'          => 0.000000,
                    'total'              => 8.500000,
                    'order_type'         => OrderType::DINING_TABLE,
                    'pos_payment_method' => PosPaymentMethod::CASH,
                    'order_datetime'     => now(),
                    'delivery_time'      => '20:30 - 21:00',
                    'preparation_time'   => 30,
                    'is_advance_order'   => 10,
                    'payment_method'     => 1,
                    'payment_status'     => 5,
                    'status'             => OrderStatus::ACCEPT,
                    'dining_table_id'    => 1,
                    'delivery_boy_id'    => null,
                    'reason'             => null,
                    'source'             => Source::POS,
                    'created_at'         => now(),
                    'updated_at'         => now()
                ],
            ]);
        }
    }
}
