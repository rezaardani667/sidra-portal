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
        'protocols',
        'host',
        'methods',
        'path',
        'expression',
        'gateway_id',
    ];

    /**
     * Relasi dengan tabel GatewayService
     */
    public function gatewayService()
    {
        return $this->belongsTo(GatewayService::class, 'gateway_id');
    }

    public function plugins()
    {
        return $this->belongsTo(Plugin::class, 'plugin_id');
    }

}
