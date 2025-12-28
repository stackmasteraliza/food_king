<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends AdminController
{
    // 1. Current Stock Balances
    public function stockBalances()
    {
        // For each item & warehouse, show current quantity and avg_cost
        $balances = DB::table('item_warehouse')
            ->join('items',        'item_warehouse.item_id',      '=', 'items.id')
            ->join('warehouses',   'item_warehouse.warehouse_id', '=', 'warehouses.id')
            ->select(
                'items.id as item_id',
                'items.name_ar as item_name',
                'warehouses.id as warehouse_id',
                'warehouses.name as warehouse_name',
                'item_warehouse.quantity',
                'item_warehouse.avg_cost'
            )
            ->orderBy('items.name_ar')
            ->get();

        return response()->json($balances);
    }

    // 2. Stock Movement History
    public function movementHistory(Request $request)
    {
        $data = $request->validate([
            'item_id'      => 'nullable|exists:items,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'date_from'    => 'required|date',
            'date_to'      => 'required|date|after_or_equal:date_from',
        ]);

        // Union purchases, supplies as "IN", disbursements as "OUT"
        $in = DB::table('purchase_invoice_items')
            ->join('purchase_invoices', 'purchase_invoice_items.purchase_invoice_id', '=', 'purchase_invoices.id')
            ->select(
                'purchase_invoices.date',
                DB::raw("'IN' as type"),
                'purchase_invoice_items.item_id',
                'purchase_invoice_items.quantity',
                'purchase_invoices.warehouse_id'
            )->whereBetween('purchase_invoices.date', [$data['date_from'], $data['date_to']]);

        $sup = DB::table('supply_items')
            ->join('supply_orders', 'supply_items.supply_order_id', '=', 'supply_orders.id')
            ->select(
                'supply_orders.date',
                DB::raw("'IN' as type"),
                'supply_items.item_id',
                'supply_items.quantity',
                'supply_orders.warehouse_id'
            )->whereBetween('supply_orders.date', [$data['date_from'], $data['date_to']]);

        $out = DB::table('disbursement_items')
            ->join('disbursement_orders', 'disbursement_items.disbursement_order_id', '=', 'disbursement_orders.id')
            ->select(
                'disbursement_orders.date',
                DB::raw("'OUT' as type"),
                'disbursement_items.item_id',
                'disbursement_items.quantity',
                'disbursement_orders.warehouse_id'
            )->whereBetween('disbursement_orders.date', [$data['date_from'], $data['date_to']]);

        $movements = $in->unionAll($sup)->unionAll($out)
            ->orderBy('date')
            ->get();

        return response()->json($movements);
    }
}
