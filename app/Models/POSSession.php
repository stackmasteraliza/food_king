<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POSSession extends Model
{
    protected $table = 'pos_sessions';
    use HasFactory;

    protected $fillable = ['shift_type_id', 'cashier_id', 'device_id', 'start_time', 'end_time', 'opening_float', 'total_sales', 'total_refunds', 'cash_expected', 'cash_actual', 'status'];

    public function shiftType()
    {
        return $this->belongsTo(ShiftType::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }



    public function cashMovements()
    {
        return $this->hasMany(CashMovement::class);
    }

    public function approval()
    {
        return $this->hasOne(SessionApproval::class);
    }
}
