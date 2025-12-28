<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'parent_shift_id', 'image'];

    public function parentShift()
    {
        return $this->belongsTo(ShiftType::class, 'parent_shift_id');
    }

    public function childShifts()
    {
        return $this->hasMany(ShiftType::class, 'parent_shift_id');
    }

    public function posSessions()
    {
        return $this->hasMany(POSSession::class);
    }
}
