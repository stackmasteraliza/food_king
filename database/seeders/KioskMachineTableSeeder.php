<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\KioskMachine;
use Illuminate\Database\Seeder;
use Dipokhalder\EnvEditor\EnvEditor;

class KioskMachineTableSeeder extends Seeder
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
            KioskMachine::insert([
                [
                    'user_id'    => 1,
                    'branch_id'  => 1,
                    'machine_id' => '12345',
                    'username'   => 'mirpur1',
                    'password'   => bcrypt('123456'),
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()

                ],
                [
                    'user_id'    => 1,
                    'branch_id'  => 2,
                    'machine_id' => '67891',
                    'username'   => 'gulshan1',
                    'password'   => bcrypt('123456'),
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ]

            ]);
        }
    }
}
