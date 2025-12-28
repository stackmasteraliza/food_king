<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionApproval extends Model
{
    use HasFactory;

    protected $fillable = ['pos_session_id', 'manager_id', 'delivered_amount', 'variance', 'status', 'comments'];

    public function posSession()
    {
        return $this->belongsTo(POSSession::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
