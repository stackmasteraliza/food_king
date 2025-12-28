<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserIntegration extends Model
{
    use HasFactory;
    protected $table = "user_integrations";
    protected $fillable = ['user_id', 'integration_id', 'api_url', 'secret_key', 'api_key', 'client_id', 'username', 'password', 'auth_method', 'creator_type', 'creator_id', 'editor_type', 'editor_id'];
    protected $casts = [
        'id'                => 'integer',
        'api_url' => 'string',
        'secret_key' => 'string',
        'api_key' => 'string',
    ];
    public function integration()
    {
        return $this->belongsTo(Integration::class);
    }
}
