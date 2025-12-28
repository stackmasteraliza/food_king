<?php

namespace Database\Seeders;

use App\Libraries\AppLibrary;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableV2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [

            // [
            //     'title'      => 'posSession',
            //     'name'       => 'posSession',
            //     'guard_name' => 'sanctum',
            //     'url'        => 'posSession',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'title'      => 'auditLog',
            //     'name'       => 'auditLog',
            //     'guard_name' => 'sanctum',
            //     'url'        => 'auditLog',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            [
                'title'      => 'AppMarketplace',
                'name'       => 'AppMarketplace',
                'guard_name' => 'sanctum',
                'url'        => 'AppMarketplace',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //  [
            //     'title'      => 'possessions',
            //     'name'       => 'possessions',
            //     'guard_name' => 'sanctum',
            //     'url'        => 'pos',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            //     'children'   => [
            //         [
            //             'title'      => 'posstart',
            //             'name'       => 'posstart',
            //             'guard_name' => 'sanctum',
            //             'url'        => 'pos/start',
            //             'created_at' => now(),
            //             'updated_at' => now(),
            //         ],
            //         [
            //             'title'      => 'shifttypes',
            //             'name'       => 'shifttypes',
            //             'guard_name' => 'sanctum',
            //             'url'        => 'pos/shift-types.index',
            //             'created_at' => now(),
            //             'updated_at' => now(),
            //         ],
            //         [
            //             'title'      => 'posactive',
            //             'name'       => 'posactive',
            //             'guard_name' => 'sanctum',
            //             'url'        => 'pos/active',
            //             'created_at' => now(),
            //             'updated_at' => now(),
            //         ],
            //         [
            //             'title'      => 'possessions',
            //             'name'       => 'possessions',
            //             'guard_name' => 'sanctum',
            //             'url'        => 'pos/sessions',
            //             'created_at' => now(),
            //             'updated_at' => now(),
            //         ],
            //         [
            //             'title'      => 'dashboard',
            //             'name'       => 'dashboard',
            //             'guard_name' => 'sanctum',
            //             'url'        => 'pos/dashboard',
            //             'created_at' => now(),
            //             'updated_at' => now(),
            //         ],
            //         [
            //             'title'      => 'cashMovement',
            //             'name'       => 'cashMovement',
            //             'guard_name' => 'sanctum',
            //             'url'        => 'pos/cashMovement',
            //             'created_at' => now(),
            //             'updated_at' => now(),
            //         ],
            //         [
            //             'title'      => 'approvals',
            //             'name'       => 'approvals',
            //             'guard_name' => 'sanctum',
            //             'url'        => 'pos/approvals',
            //             'created_at' => now(),
            //             'updated_at' => now(),
            //         ],

            //     ]
            // ],

            // [
            //     'title'      => 'InventoryReport',
            //     'name'       => 'InventoryReport',
            //     'guard_name' => 'sanctum',
            //     'url'        => 'InventoryReport',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'title'      => 'OpeningBalanceForm',
            //     'name'       => 'OpeningBalanceForm',
            //     'guard_name' => 'sanctum',
            //     'url'        => 'OpeningBalanceForm',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'title'      => 'PurchaseInvoiceForm',
            //     'name'       => 'PurchaseInvoiceForm',
            //     'guard_name' => 'sanctum',
            //     'url'        => 'PurchaseInvoiceForm',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'title'      => 'SupplyOrderForm',
            //     'name'       => 'SupplyOrderForm',
            //     'guard_name' => 'sanctum',
            //     'url'        => 'SupplyOrderForm',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'title'      => 'DisbursementOrderForm',
            //     'name'       => 'DisbursementOrderForm',
            //     'guard_name' => 'sanctum',
            //     'url'        => 'DisbursementOrderForm',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
        ];

        $permissions = AppLibrary::associativeToNumericArrayBuilder($permissions);
        Permission::insert($permissions);
    }
}
