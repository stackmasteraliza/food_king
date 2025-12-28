<?php

namespace Database\Seeders;

use App\Libraries\AppLibrary;
use App\Models\Menu;
use Illuminate\Database\Seeder;


class MenuV2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = [

            // [
            //     'name'       => 'possession',
            //     'language'   => 'possession',
            //     'url'        => 'posSession',
            //     'icon'       => 'lab lab-pos',
            //     'priority'   => 100,
            //     'status'     => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            //     'parent'     => 4
            // ],
            // [
            //     'name'       => 'auditLog',
            //     'language'   => 'auditLog',
            //     'url'        => 'auditLog',
            //     'icon'       => 'lab lab-pos',
            //     'priority'   => 100,
            //     'status'     => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            //     'parent'     => 4
            // ],
            [
                'name'       => 'AppMarketplace',
                'language'   => 'AppMarketplace',
                'url'        => 'AppMarketplace',
                'icon'       => 'lab lab-pos',
                'priority'   => 100,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'parent'     => 4
            ],
            // [
            //     'name'       => 'possessions',
            //     'language'   => 'possessions',
            //     'url'        => '#',
            //     'icon'       => 'lab ',
            //     'priority'   => 100,
            //     'status'     => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            //     'children'   => [
            //         [
            //             'name'       => 'pos.start',
            //             'language'   => 'pos.start',
            //             'url'        => 'pos.start',
            //             'icon'       => 'lab lab-pos',
            //             'priority'   => 100,
            //             'status'     => 1,
            //             'created_at' => now(),
            //             'updated_at' => now(),

            //         ],
            //         [
            //             'name'       => 'pos.cashMovement',
            //             'language'   => 'pos.cashMovement',
            //             'url'        => 'pos.cashMovement',
            //             'icon'       => 'lab lab-pos',
            //             'priority'   => 100,
            //             'status'     => 1,
            //             'created_at' => now(),
            //             'updated_at' => now(),

            //         ],
            //         [
            //             'name'       => 'pos.approvals',
            //             'language'   => 'pos.approvals',
            //             'url'        => 'pos.approvals',
            //             'icon'       => 'lab lab-pos',
            //             'priority'   => 100,
            //             'status'     => 1,
            //             'created_at' => now(),
            //             'updated_at' => now(),

            //         ],
            //         [
            //             'name'       => 'shift-types.index',
            //             'language'   => 'shift-types.index',
            //             'url'        => 'shift-types.index',
            //             'icon'       => 'lab lab-pos',
            //             'priority'   => 100,
            //             'status'     => 1,
            //             'created_at' => now(),
            //             'updated_at' => now(),

            //         ],
            //         [
            //             'name'       => 'pos.active',
            //             'language'   => 'pos.active',
            //             'url'        => 'pos.active',
            //             'icon'       => 'lab lab-pos',
            //             'priority'   => 100,
            //             'status'     => 1,
            //             'created_at' => now(),
            //             'updated_at' => now(),

            //         ],
            //         [
            //             'name'       => 'pos.sessions',
            //             'language'   => 'pos.sessions',
            //             'url'        => 'pos.sessions',
            //             'icon'       => 'lab lab-pos',
            //             'priority'   => 100,
            //             'status'     => 1,
            //             'created_at' => now(),
            //             'updated_at' => now(),

            //         ],
            //         [
            //             'name'       => 'pos.dashboard',
            //             'language'   => 'pos.dashboard',
            //             'url'        => 'pos.dashboard',
            //             'icon'       => 'lab lab-pos',
            //             'priority'   => 100,
            //             'status'     => 1,
            //             'created_at' => now(),
            //             'updated_at' => now(),

            //         ],
            //     ]
            // ],
            // [

            //     'name'       => 'DisbursementOrderForm',
            //     'language'   => 'DisbursementOrderForm',
            //     'url'        => 'DisbursementOrderForm',
            //     'icon'       => 'lab lab-pos',
            //     'priority'   => 100,
            //     'status'     => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            //     'parent'     => 4
            // ],
            // [

            //     'name'       => 'SupplyOrderForm',
            //     'language'   => 'SupplyOrderForm',
            //     'url'        => 'SupplyOrderForm',
            //     'icon'       => 'lab lab-pos',
            //     'priority'   => 100,
            //     'status'     => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            //     'parent'     => 4
            // ],
            // [

            //     'name'       => 'PurchaseInvoiceForm',
            //     'language'   => 'PurchaseInvoiceForm',
            //     'url'        => 'PurchaseInvoiceForm',
            //     'icon'       => 'lab lab-pos',
            //     'priority'   => 100,
            //     'status'     => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            //     'parent'     => 4
            // ],
            // [

            //     'name'       => 'OpeningBalanceForm',
            //     'language'   => 'OpeningBalanceForm',
            //     'url'        => 'OpeningBalanceForm',
            //     'icon'       => 'lab lab-pos',
            //     'priority'   => 100,
            //     'status'     => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            //     'parent'     => 4
            // ],
            // [

            //     'name'       => 'InventoryReport',
            //     'language'   => 'InventoryReport',
            //     'url'        => 'InventoryReport',
            //     'icon'       => 'lab lab-pos',
            //     'priority'   => 100,
            //     'status'     => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            //     'parent'     => 4
            // ],
        ];

        Menu::insert(AppLibrary::associativeToNumericArrayBuilder($menus));
    }
}
