<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashMovement extends Model
{
    use HasFactory;

    protected $fillable = ['pos_session_id', 'amount', 'type', 'description', 'timestamp'];

    public function posSession()
    {
        return $this->belongsTo(POSSession::class);
    }
}
