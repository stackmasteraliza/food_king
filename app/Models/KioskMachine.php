<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KioskMachine extends Model
{
    protected $table = "kiosk_machines";
    protected $fillable = ['user_id', 'branch_id', 'machine_id', 'username', 'password', 'is_login', 'status'];
    protected $casts = [
        'id'         => 'integer',
        'user_id'    => 'integer',
        'branch_id'  => 'integer',
        'machine_id' => 'string',
        'username'   => 'string',
        'password'   => 'string',
        'is_login'   => 'integer',
        'status'     => 'integer',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}