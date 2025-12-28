<?php

namespace Database\Seeders;

use App\Enums\InputType;
use App\Models\SmsGateway;
use App\Models\GatewayOption;
use Illuminate\Database\Seeder;


class SmsGatewayTableSeederVersionTwo extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */



    public function run(): void
    {
        $msg91Gateway = SmsGateway::where('slug', 'msg91')->first();
        if ($msg91Gateway) {
            GatewayOption::create([
                'model_id'   => $msg91Gateway->id,
                'model_type' => 'App\Models\SmsGateway',
                'option'     => 'msg91_template_variable',
                'value'      => "",
                'type'       => InputType::TEXT,
                'activities' => json_encode('')
            ]);
        } else {
            $this->command->info('Msg91 gateway not found.');
        }
    }
}
