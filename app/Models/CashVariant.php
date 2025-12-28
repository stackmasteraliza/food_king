<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashVariant extends Model
{
    use HasFactory;

    protected $fillable = ['pos_session_id', 'amount', 'reason', 'timestamp'];

    public function posSession()
    {
        return $this->belongsTo(POSSession::class);
    }
}
