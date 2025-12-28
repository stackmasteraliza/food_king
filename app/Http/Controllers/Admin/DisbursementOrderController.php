<?php

namespace App\Http\Controllers\Admin;

use App\Models\DisbursementOrder;
use App\Models\DisbursementItem;
use Illuminate\Http\Request;
use App\Services\StockService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DisbursementOrderController extends adminController
{
    // List all disbursement orders
    public function index()
    {
        return DisbursementOrder::with('items.item', 'warehouse')->get();
    }

    // Store a new disbursement order
    public function store(Request $request)
    {
        $data = $request->validate([
            'warehouse_id'    => 'required|exists:warehouses,id',
            'date'            => 'required|date',
            'reason'          => 'nullable|string',
            'items'           => 'required|array|min:1',
            'items.*.item_id'   => 'required|exists:items,id',
            'items.*.quantity'  => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            // Generate order number
            $orderNo = 'DO-' . Str::upper(Str::random(6));

            // Create disbursement order
            $order = DisbursementOrder::create([
                'order_no'     => $orderNo,
                'warehouse_id' => $data['warehouse_id'],
                'reason'       => $data['reason'] ?? null,
                'date'         => $data['date'],
            ]);

            // Create line items and remove stock
            foreach ($data['items'] as $row) {
                DisbursementItem::create([
                    'disbursement_order_id' => $order->id,
                    'item_id'               => $row['item_id'],
                    'quantity'              => $row['quantity'],
                    'note'                  => $row['note'] ?? null,
                ]);

                // Remove from stock
                StockService::removeStock(
                    $row['item_id'],
                    $data['warehouse_id'],
                    $row['quantity']
                );
            }

            DB::commit();
            return response()->json(['success' => true, 'data' => $order->load('items')], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
