<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Warehouse;

class StockService
{
    public static function addStock($itemId, $warehouseId, $quantity, $unitCost)
    {
        $item = Item::findOrFail($itemId);
        $warehouse = Warehouse::findOrFail($warehouseId);

        $stock = $item->warehouses()->where('warehouse_id', $warehouseId)->first();

        if ($stock) {
            $currentQty = $stock->pivot->quantity;
            $currentCost = $stock->pivot->avg_cost;

            $newQty = $currentQty + $quantity;
            $newCost = (($currentQty * $currentCost) + ($quantity * $unitCost)) / $newQty;

            $item->warehouses()->updateExistingPivot($warehouseId, [
                'quantity' => $newQty,
                'avg_cost' => $newCost,
            ]);
        } else {
            $item->warehouses()->attach($warehouseId, [
                'quantity' => $quantity,
                'avg_cost' => $unitCost,
            ]);
        }
    }

    public static function removeStock($itemId, $warehouseId, $quantity)
    {
        $item = Item::findOrFail($itemId);
        $stock = $item->warehouses()->where('warehouse_id', $warehouseId)->first();

        if (!$stock || $stock->pivot->quantity < $quantity) {
            throw new \Exception('Insufficient stock for item: ' . $item->name_ar);
        }

        $newQty = $stock->pivot->quantity - $quantity;

        $item->warehouses()->updateExistingPivot($warehouseId, [
            ' quantity' => $newQty,
        ]);
    }
}
