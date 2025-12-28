<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Models\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = "orders";
    protected $fillable = [
        'order_serial_no',
        'token',
        'user_id',
        'branch_id',
        'subtotal',
        'discount',
        'delivery_charge',
        'total_tax',
        'total',
        'order_type',
        'order_datetime',
        'delivery_time',
        'preparation_time',
        'is_advance_order',
        'address',
        'payment_method',
        'payment_status',
        'status',
        'dining_table_id',
        'source',
        'pos_payment_method',
        'pos_payment_note',
        'pos_received_amount',
        'Reservations',
        'creator_type',
        'creator_id',
        'editor_type',
        'editor_id',
        'created_at',
        'updated_at',
        'NetworkAmount'
    ];

    protected $casts = [
        'id'                  => 'integer',
        'order_serial_no'     => 'string',
        'token'               => 'string',
        'user_id'             => 'integer',
        'branch_id'           => 'integer',
        'subtotal'            => 'decimal:6',
        'discount'            => 'decimal:6',
        'delivery_charge'     => 'decimal:6',
        'total_tax'           => 'decimal:6',
        'total'               => 'decimal:6',
        'order_type'          => 'integer',
        'order_datetime'      => 'datetime',
        'delivery_time'       => 'string',
        'preparation_time'    => 'integer',
        'is_advance_order'    => 'integer',
        'payment_method'      => 'integer',
        'payment_status'      => 'integer',
        'status'              => 'integer',
        'dining_table_id'     => 'integer',
        'source'              => 'integer',
        'pos_payment_method'  => 'integer',
        'pos_payment_note'    => 'string',
        'pos_received_amount' => 'decimal:6',
        'creator_type'         => 'string',
        'creator_id'           => 'integer',
        'editor_type'          => 'string',
        'editor_id'            => 'integer',
        'created_at'           => 'datetime',
        'updated_at'           => 'datetime',
        'NetworkAmount' => 'decimal:6'
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new BranchScope());
    }

    public function orderItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function items(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'order_items')->withTrashed();
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function address(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(OrderAddress::class);
    }
    public function Reservations(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(OrderReservationsData::class);
    }
    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function deliveryBoy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'delivery_boy_id', 'id');
    }

    public function coupon(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(OrderCoupon::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', OrderStatus::PENDING);
    }

    public function scopeAccept($query)
    {
        return $query->where('status', OrderStatus::ACCEPT);
    }

    public function scopePreparing($query)
    {
        return $query->where('status', OrderStatus::PREPARING);
    }

    public function scopePrepared($query)
    {
        return $query->where('status', OrderStatus::PREPARED);
    }

    public function scopeOutForDelivery($query)
    {
        return $query->where('status', OrderStatus::OUT_FOR_DELIVERY);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', OrderStatus::DELIVERED);
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', OrderStatus::CANCELED);
    }

    public function scopeReturned($query)
    {
        return $query->where('status', OrderStatus::RETURNED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', OrderStatus::REJECTED);
    }

    public function transaction(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    public function diningTable(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DiningTable::class);
    }
}
