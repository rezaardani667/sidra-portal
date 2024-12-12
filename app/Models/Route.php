<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'routes';

    protected $fillable = [
        'name',
        'tags',
        'protocol',
        'hosts',
        'methods',
        'paths',
        'snis',
        'headers',
        'expression',
        'gateway_id',
    ];

    protected $casts = [
        'paths' => 'array',
        'methods' => 'array',
        'hosts' => 'array',
        'snis' => 'array',
        'headers' => 'array',
    ];

    public function gatewayService()
    {
        return $this->belongsTo(GatewayService::class, 'gateway_id');
    }

    public function plugins()
    {
        return $this->hasMany(Plugin::class, 'plugin_id');
    }

}
