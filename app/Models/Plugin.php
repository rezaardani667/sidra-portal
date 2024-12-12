<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plugin extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'plugins';

    protected $fillable = [
        'name',
        'type_plugin',
        'enabled',
        'config',
        'protocols',
        'gateway_id',
        'routes_id',
        'applied_to',
    ];

    protected $casts = [
        'protocols' => 'array',
    ];

    public function gatewayService()
    {
        return $this->belongsTo(GatewayService::class, 'gateway_id');
    }

    public function consumers()
    {
        return $this->hasMany(Consumer::class);
    }

    public function routes()
    {
        return $this->hasMany(Route::class, 'route_id');
    }
}
