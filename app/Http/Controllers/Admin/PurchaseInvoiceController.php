<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use Illuminate\Http\Request;
use App\Services\StockService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PurchaseInvoiceController extends Controller
{
    // List all purchase invoices
    public function index()
    {
        return PurchaseInvoice::with('items.item', 'warehouse')->get();
    }

    // Store a new purchase invoice
    public function store(Request $request)
    {
        $data = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'date'         => 'required|date',
            'items'        => 'required|array|min:1',
            'items.*.item_id'   => 'required|exists:items,id',
            'items.*.quantity'  => 'required|numeric|min:0.01',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        // Wrap in transaction
        DB::beginTransaction();
        try {
            // Generate invoice number
            $invoiceNo = 'PI-' . Str::upper(Str::random(6));

            // Create invoice
            $invoice = PurchaseInvoice::create([
                'invoice_no'  => $invoiceNo,
                'warehouse_id' => $data['warehouse_id'],
                'date'        => $data['date'],
                'total'       => 0, // will update below
            ]);

            $totalInvoice = 0;
            // Create line items and update stock
            foreach ($data['items'] as $row) {
                $lineTotal = $row['quantity'] * $row['unit_cost'];
                PurchaseInvoiceItem::create([
                    'purchase_invoice_id' => $invoice->id,
                    'item_id'             => $row['item_id'],
                    'quantity'            => $row['quantity'],
                    'unit_cost'           => $row['unit_cost'],
                    'total_cost'          => $lineTotal,
                ]);

                // Add stock
                StockService::addStock(
                    $row['item_id'],
                    $data['warehouse_id'],
                    $row['quantity'],
                    $row['unit_cost']
                );

                $totalInvoice += $lineTotal;
            }

            // Update invoice total
            $invoice->update(['total' => $totalInvoice]);

            DB::commit();
            return response()->json(['success' => true, 'data' => $invoice->load('items')], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
