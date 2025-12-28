<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosShift extends Model
{
    use HasFactory;
    protected $table = "pos_shifts";
    protected $fillable = [
        'shift_id',
        'pos_id',
        'session_number',
        'creator_id',
        'ended_at',
        'status',
        'closing_balance',
        'cashier_id'
    ];

    protected $casts = [
        'id'          => 'integer',
        'shift_id'       => 'integer',
        'pos_id'      => 'integer',
        'session_number'       => 'string',
        'creator_id'      => 'integer',
        'ended_at'      => 'datetime',
    ];
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function device()
    {
        return $this->belongsTo(PosDevice::class, 'pos_id');
    }
}
