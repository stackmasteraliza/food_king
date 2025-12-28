<?php

namespace Database\Seeders;

use App\Enums\SwitchBox;
use Illuminate\Database\Seeder;
use App\Models\NotificationAlert;

class NotificationAlertTableSeederTwo extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        NotificationAlert::updateOrCreate(
            ['language' => 'order_prepared_message'],
            [
                'name'                      => 'Order Prepared Message',
                'mail_message'              => 'The order has been prepared and is waiting for delivery.',
                'sms_message'               => 'The order has been prepared and is waiting for delivery.',
                'push_notification_message' => 'The order has been prepared and is waiting for delivery.',
                'mail'                      => SwitchBox::OFF,
                'sms'                       => SwitchBox::OFF,
                'push_notification'         => SwitchBox::OFF,
            ]
        );

        NotificationAlert::where('name', 'Order Processing Message')
            ->update([
                'name'     => 'Order Preparing Message',
                'language' => 'order_preparing_message',
            ]);
    }
}