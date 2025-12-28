<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturnItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_return_id',
        'invoice_item_id',
        'quantity',
        'price',
        'reason'
    ];

    public function salesReturn()
    {
        return $this->belongsTo(SalesReturn::class);
    }

    public function invoiceItem()
    {
        return $this->belongsTo(InvoiceItem::class);
    }
}
