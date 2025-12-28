<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    use HasFactory;
    protected $table = "integrations";
    protected $fillable = ['name', 'icon_url', 'description', 'price', 'trial_days', 'features', 'active', 'creator_type', 'creator_id', 'editor_type', 'editor_id'];

    protected $casts = [
        'id'                => 'integer',
        'name'  => 'string',
        'features' => 'string',
        'active' => 'boolean',
        'icon_url'  =>  'string',
        'description'  =>   'string',
        'price'  =>  'decimal:3',
    ];
    public function userIntegration()
    {
        return $this->hasOne(UserIntegration::class)->where('user_id', auth()->id());
    }
}
