<?php

namespace App\Http\Controllers\Admin;

use App\Models\SupplyOrder;
use App\Models\SupplyItem;
use Illuminate\Http\Request;
use App\Services\StockService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SupplyOrderController extends AdminController
{
    // List all supply orders
    public function index()
    {
        return SupplyOrder::with('items.item', 'warehouse')->get();
    }

    // Store a new supply order
    public function store(Request $request)
    {
        $data = $request->validate([
            'warehouse_id'    => 'required|exists:warehouses,id',
            'date'            => 'required|date',
            'source'          => 'nullable|string',
            'items'           => 'required|array|min:1',
            'items.*.item_id'   => 'required|exists:items,id',
            'items.*.quantity'  => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            // Generate supply order number
            $orderNo = 'SO-' . Str::upper(Str::random(6));

            // Create supply order
            $order = SupplyOrder::create([
                'order_no'     => $orderNo,
                'warehouse_id' => $data['warehouse_id'],
                'source'       => $data['source'] ?? null,
                'date'         => $data['date'],
            ]);

            // Create line items and add stock
            foreach ($data['items'] as $row) {
                SupplyItem::create([
                    'supply_order_id' => $order->id,
                    'item_id'         => $row['item_id'],
                    'quantity'        => $row['quantity'],
                    'note'            => $row['note'] ?? null,
                ]);

                // Add to stock at zero cost (or you could supply a cost field)
                StockService::addStock(
                    $row['item_id'],
                    $data['warehouse_id'],
                    $row['quantity'],
                    0
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
