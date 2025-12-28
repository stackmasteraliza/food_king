<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'location', 'status'];

    public function items()
    {
        return $this->belongsToMany(Item::class)->withPivot(['quantity', 'avg_cost'])->withTimestamps();
    }

    public function purchaseInvoices()
    {
        return $this->hasMany(PurchaseInvoice::class);
    }
}

class Item extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'name_ar', 'name_en', 'unit', 'reorder_level'];

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class)->withPivot(['quantity', 'avg_cost'])->withTimestamps();
    }

    public function openingBalances()
    {
        return $this->hasMany(OpeningBalance::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseInvoiceItem::class);
    }

    public function disbursementItems()
    {
        return $this->hasMany(DisbursementItem::class);
    }

    public function supplyItems()
    {
        return $this->hasMany(SupplyItem::class);
    }
}

class OpeningBalance extends Model
{
    use HasFactory;
    protected $fillable = ['item_id', 'warehouse_id', 'quantity', 'cost', 'date'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}

class PurchaseInvoice extends Model
{
    use HasFactory;
    protected $fillable = ['invoice_no', 'warehouse_id', 'date', 'total'];

    public function items()
    {
        return $this->hasMany(PurchaseInvoiceItem::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}

class PurchaseInvoiceItem extends Model
{
    use HasFactory;
    protected $fillable = ['purchase_invoice_id', 'item_id', 'quantity', 'unit_cost', 'total_cost'];

    public function invoice()
    {
        return $this->belongsTo(PurchaseInvoice::class);
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

class DisbursementOrder extends Model
{
    use HasFactory;
    protected $fillable = ['order_no', 'warehouse_id', 'reason', 'date'];

    public function items()
    {
        return $this->hasMany(DisbursementItem::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}

class DisbursementItem extends Model
{
    use HasFactory;
    protected $fillable = ['disbursement_order_id', 'item_id', 'quantity', 'note'];

    public function order()
    {
        return $this->belongsTo(DisbursementOrder::class);
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

class SupplyOrder extends Model
{
    use HasFactory;
    protected $fillable = ['order_no', 'warehouse_id', 'source', 'date'];

    public function items()
    {
        return $this->hasMany(SupplyItem::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}

class SupplyItem extends Model
{
    use HasFactory;
    protected $fillable = ['supply_order_id', 'item_id', 'quantity', 'note'];

    public function order()
    {
        return $this->belongsTo(SupplyOrder::class);
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
