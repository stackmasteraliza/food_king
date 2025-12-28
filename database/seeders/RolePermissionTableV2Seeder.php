<?php

namespace Database\Seeders;

use App\Enums\Role as EnumRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RolePermissionTableV2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::find(EnumRole::ADMIN);
        $adminRole?->givePermissionTo(Permission::all());

        $branchManager = Role::find(EnumRole::BRANCH_MANAGER);
        if ($branchManager) {
            $branchManagerPermissions = [
                ['name' => 'posSession'],
                ['name' => 'auditLog'],
                  ['name' => 'AppMarketplace'],
                ['name' => 'posapprovals'],
                ['name' => 'poscashMovement'],
                ['name' => 'posdashboard'],
                ['name' => 'posactive'],
                ['name' => 'posstart'],
                ['name' => 'shifttypes'],
                ['name' => 'InventoryReport'],
                ['name' => 'OpeningBalanceForm'],
                ['name' => 'PurchaseInvoiceForm'],
                ['name' => 'SupplyOrderForm'],
                ['name' => 'DisbursementOrderForm'],
            ];
            $branchManagerPermissions = Permission::whereIn('name', $branchManagerPermissions)->get();
            $branchManager->givePermissionTo($branchManagerPermissions);
        }
    }
}
