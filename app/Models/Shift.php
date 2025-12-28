<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift  extends Model
{
    use HasFactory;
    protected $table = "shifts";
    protected $fillable = ['name', 'status'];
    protected $casts = [
        'id'          => 'integer',
        'name'       => 'string',
        'status'      => 'integer',
    ];
}
