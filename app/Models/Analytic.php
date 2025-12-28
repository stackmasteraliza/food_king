<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Analytic extends Model
{
    use HasFactory;
    use Loggable; // Add this trait

    protected $table = "analytics";
    protected $fillable = ['name', 'status'];
    protected $casts = [
        'id'     => 'integer',
        'name'   => 'string',
        'status' => 'integer',
    ];


    public function analyticSections(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AnalyticSection::class, 'analytic_id', 'id');
    }
}
