<?php

namespace Database\Seeders;

use App\Enums\OrderType;
use App\Enums\Source;
use App\Models\Order;
use Illuminate\Database\Seeder;

class PosOrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::where('source', Source::POS)->update(['order_type' => OrderType::TAKEAWAY]);
    }
}