<?php

namespace App\Http\Controllers\Admin;

use App\Models\OpeningBalance;
use Illuminate\Http\Request;
use App\Services\StockService;

class OpeningBalanceController extends AdminController
{
    public function index()
    {
        return OpeningBalance::with('item', 'warehouse')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'item_id' => 'required|exists:items,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|numeric|min:0.01',
            'cost' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        // Save opening balance
        $opening = OpeningBalance::create($data);

        // Add to stock
        StockService::addStock($data['item_id'], $data['warehouse_id'], $data['quantity'], $data['cost']);

        return response()->json(['success' => true, 'data' => $opening]);
    }
}
