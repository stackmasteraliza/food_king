<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Smartisan\Settings\Facades\Settings;


class NotificationTableSeederTwo extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Settings::group('notification')->forget([
            'notification_fcm_secret_key'
        ]);

        Settings::group('notification')->set([
            'notification_fcm_json_file' => ''
        ]);
    }
}
