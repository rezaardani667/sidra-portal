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
        'consumers_id',
        'enabled',
        'config',
        'protocols',
        'gateway_id',
        'routes_id',
        'applied_to',
    ];

    protected $casts = [
        'protocols' => 'array',
        'config' => 'array',
    ];

    public function consumers()
    {
        return $this->hasMany(Consumer::class);
    }

    public function gatewayService()
    {
        return $this->belongsToMany(GatewayService::class, 'plugin_service_route', 'plugins_id', 'gateway_id')
        ->using(PluginServiceRoute::class)      
        ->withTimestamps();
    }

    public function routes()
    {
        return $this->belongsToMany(Route::class, 'plugin_service_route', 'plugins_id', 'routes_id')
        ->using(PluginServiceRoute::class)      
        ->withTimestamps();
    }
}
