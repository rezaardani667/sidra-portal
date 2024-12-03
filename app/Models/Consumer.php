<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consumer extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'consumers';

    protected $fillable = [
        'username',
        'custom_id',
        'tags',
        'gateway_id',
        'plugin_id',
    ];

    /**
     * Relasi dengan tabel GatewayService
     */
    public function gatewayService()
    {
        return $this->belongsTo(GatewayService::class, 'gateway_id');
    }

    /**
     * Relasi dengan tabel Plugin
     */
    public function plugin()
    {
        return $this->belongsTo(Plugin::class);
    }
}
