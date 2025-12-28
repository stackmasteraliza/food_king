<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosDevice extends Model
{
    use HasFactory;
    protected $table = "pos_devices";
    protected  $fillable = ['name', 'identifier'];
    protected $casts = [
        'id'          => 'integer',
        'name'       => 'string',
        'identifier'      => 'string',
    ];
}
