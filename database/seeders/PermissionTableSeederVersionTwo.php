<?php

namespace Database\Seeders;

use App\Libraries\AppLibrary;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Enums\Role as EnumRole;
use Spatie\Permission\Models\Role;

class PermissionTableSeederVersionTwo extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'title'      => 'K.D.S',
                'name'       => 'kitchen-display-system',
                'guard_name' => 'sanctum',
                'url'        => 'kitchen-display-system',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title'      => 'O.S.S',
                'name'       => 'order-status-screen',
                'guard_name' => 'sanctum',
                'url'        => 'order-status-screen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title'      => 'Waiters',
                'name'       => 'waiters',
                'guard_name' => 'sanctum',
                'url'        => 'waiters',
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'title'      => 'Waiters Create',
                        'name'       => 'waiters_create',
                        'guard_name' => 'sanctum',
                        'url'        => 'waiters/create',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Waiters Edit',
                        'name'       => 'waiters_edit',
                        'guard_name' => 'sanctum',
                        'url'        => 'waiters/edit',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Waiters Delete',
                        'name'       => 'waiters_delete',
                        'guard_name' => 'sanctum',
                        'url'        => 'waiters/delete',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Waiters Show',
                        'name'       => 'waiters_show',
                        'guard_name' => 'sanctum',
                        'url'        => 'waiters/show',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            ],
            [
                'title'      => 'Chefs',
                'name'       => 'chefs',
                'guard_name' => 'sanctum',
                'url'        => 'chefs',
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'title'      => 'Chefs Create',
                        'name'       => 'chefs_create',
                        'guard_name' => 'sanctum',
                        'url'        => 'chefs/create',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Chefs Edit',
                        'name'       => 'chefs_edit',
                        'guard_name' => 'sanctum',
                        'url'        => 'chefs/edit',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Chefs Delete',
                        'name'       => 'chefs_delete',
                        'guard_name' => 'sanctum',
                        'url'        => 'chefs/delete',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title'      => 'Chefs Show',
                        'name'       => 'chefs_show',
                        'guard_name' => 'sanctum',
                        'url'        => 'chefs/show',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            ],
        ];

        $permissions = AppLibrary::associativeToNumericArrayBuilder($permissions);
        Permission::insert($permissions);

        $adminPermissions = [
            ['name' => 'kitchen-display-system'],
            ['name' => 'order-status-screen'],
            ['name' => 'waiters'],
            ['name' => 'waiters_create'],
            ['name' => 'waiters_edit'],
            ['name' => 'waiters_delete'],
            ['name' => 'waiters_show'],
            ['name' => 'chefs'],
            ['name' => 'chefs_create'],
            ['name' => 'chefs_edit'],
            ['name' => 'chefs_delete'],
            ['name' => 'chefs_show']
        ];

        $adminRole = Role::find(EnumRole::ADMIN);
        $adminPermissions = Permission::whereIn('name', $adminPermissions)->get();
        $adminRole?->givePermissionTo($adminPermissions);

        $chefsPermissions = [
            ['name' => 'dashboard'],
            ['name' => 'kitchen-display-system'],
            ['name' => 'order-status-screen'],
        ];

        $chefRole = Role::find(EnumRole::CHEF);
        $chefsPermissions = Permission::whereIn('name', $chefsPermissions)->get();
        $chefRole?->givePermissionTo($chefsPermissions);
    }
}