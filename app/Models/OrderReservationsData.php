<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReservationsData extends Model
{
    use HasFactory;

    protected $table = "order_Reservations_data";
    protected $fillable = [
        'order_id',
        'customer_id',
        'ReservationsDate',
        'ReservationsAmount',
        'notes'
    ];

    protected $casts = [
        'id'        => 'integer',
        'order_id'  => 'integer',
        'customer_id'   => 'integer',
        'ReservationsDate'     => 'datetime',
        'ReservationsAmount'   => 'decimal:2',
        'notes' => 'string',

    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id')->withTrashed();
    }

}
