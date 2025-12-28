<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Smartisan\Settings\Facades\Settings;

class SiteTableSeederOne extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::group('site')->set([
            'site_default_phone_digit_length' => 10,
        ]);

        Artisan::call('optimize:clear');
    }
}
