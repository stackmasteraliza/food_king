<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Address extends Model
{
    use HasFactory;
    use Loggable; // Add this trait

    protected $table = "addresses";
    protected $fillable    = ['label', 'address', 'user_id', 'apartment', 'latitude', 'longitude'];
    protected $casts = [
        'id'        => 'integer',
        'label'     => 'string',
        'address'   => 'string',
        'user_id'   => 'integer',
        'apartment' => 'string',
        'latitude'  => 'string',
        'longitude' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
