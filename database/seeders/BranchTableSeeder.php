<?php

namespace Database\Seeders;

use Dipokhalder\EnvEditor\EnvEditor;
use App\Enums\Status;
use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Branch::create([
            'name'      => 'Mirpur-1 (main)',
            'email'     => 'mirpur@inilabs.xyz',
            'phone'     => '+536464646464',
            'latitude'  => 23.8042375,
            'longitude' => 90.3525979,
            'zone'      => json_encode('[{"lat":23.804898425688478,"lng":90.34644025387266},{"lat":23.80898185164042,"lng":90.36085980953672},{"lat":23.7979091872033,"lng":90.36721128048399},{"lat":23.795239041774114,"lng":90.35854238094785},{"lat":23.79186662551694,"lng":90.35257944558866},{"lat":23.80081948198351,"lng":90.34502634500272}]'),
            'city'      => 'Mirpur-1',
            'state'     => 'Dhaka',
            'zip_code'  => '1216',
            'address'   => 'House : 25, Road No: 2, Block A, Mirpur-1, Dhaka 1216',
            'status'    => Status::ACTIVE,
        ]);

        $envService = new EnvEditor();
        if ($envService->getValue('DEMO')) {
            Branch::create([
                'name'      => 'Gulshan-1',
                'email'     => 'gulshan@inilabs.xyz',
                'phone'     => '+1243535366',
                'latitude'  => 23.7806612,
                'longitude' => 90.40614,
                'zone'      => json_encode('[{"lat":23.785498859040572,"lng":90.41104035134859},{"lat":23.78706965718931,"lng":90.42666153665132},{"lat":23.77379581545051,"lng":90.4282064890439},{"lat":23.77348162526697,"lng":90.41043953652925},{"lat":23.781728866043498,"lng":90.40400223489351}]'),
                'city'      => 'Gulshan-1',
                'state'     => 'Dhaka',
                'zip_code'  => '1212',
                'address'   => '1st floor, Adam Building, House: 41 Road: 52, Dhaka 1212',
                'status'    => Status::ACTIVE,
            ]);
        }
    }
}