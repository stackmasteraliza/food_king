<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Branch extends Model
{
    use HasFactory;
    use Loggable; // Add this trait
    protected $table = "branches";
    protected $fillable = ['name', 'email', 'phone', 'latitude', 'longitude', 'city', 'state', 'zip_code', 'address', 'zone', 'status'];
    protected $casts = [
        'id'        => 'integer',
        'name'      => 'string',
        'email'     => 'string',
        'phone'     => 'string',
        'latitude'  => 'string',
        'longitude' => 'string',
        'city'      => 'string',
        'state'     => 'string',
        'zip_code'  => 'string',
        'address'   => 'string',
        'zone'      => 'string',
        'status'    => 'integer',
    ];
}
