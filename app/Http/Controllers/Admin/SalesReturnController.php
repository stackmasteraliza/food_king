<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\SalesReturn;
use Illuminate\Http\Request;

class SalesReturnController extends Controller
{
    public function getInvoice($invoiceNumber)
    {
        $invoice = Invoice::with(['items', 'customer'])
            ->where('invoice_number', $invoiceNumber)
            ->firstOrFail();

        return response()->json([
            'id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'date' => $invoice->date,
            'customer_name' => $invoice->customer->name,
            'items' => $invoice->items->map(function($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_code' => $item->product->code,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->total,
                ];
            })
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:invoice_items,id',
            'items.*.return_quantity' => 'required|numeric|min:1',
            'items.*.return_reason' => 'required|string',
        ]);

        // إنشاء مرتجع المبيعات
        $salesReturn = SalesReturn::create([
            'invoice_id' => $request->invoice_id,
            'return_date' => now(),
            'total_amount' => 0, // سيتم حسابها لاحقاً
        ]);

        // إضافة الأصناف المرتجعة
        $totalAmount = 0;

        foreach ($request->items as $item) {
            $salesReturn->items()->create([
                'invoice_item_id' => $item['id'],
                'quantity' => $item['return_quantity'],
                'price' => $item['price'],
                'reason' => $item['return_reason'],
            ]);

            $totalAmount += $item['return_quantity'] * $item['price'];
        }

        // تحديث المبلغ الإجمالي
        $salesReturn->update(['total_amount' => $totalAmount]);

        return response()->json([
            'return_id' => $salesReturn->id,
            'message' => 'تم حفظ المرتجع بنجاح'
        ]);
    }

    public function print($id)
    {
        $salesReturn = SalesReturn::with(['invoice', 'items'])->findOrFail($id);
        return view('sales-returns.print', compact('salesReturn'));
    }
}
