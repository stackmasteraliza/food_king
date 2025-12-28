<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'return_date',
        'total_amount',
        'notes'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function items()
    {
        return $this->hasMany(SalesReturnItem::class);
    }
}
