<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GatewayService extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'gateway_services';

    protected $fillable = [
        'data_plane_id',
        'name',
        'tags',
        'protocol',
        'host',
        'public_key',
        'connect_timeout',
        'read_timeout',
        'write_timeout',
        'retries',
        'ca_certificates',
        'client_certificate',
    ];

    /**
     * Relationship: Many GatewayServices belong to one DataPlaneNode.
     */
    public function dataPlaneNode()
    {
        return $this->belongsTo(DataPlaneNodes::class, 'data_plane_id');
    }

    /**
     * Relasi dengan tabel Routes
     */
    public function routes()
    {
        return $this->hasMany(Route::class, 'gateway_id');
    }

    /**
     * Relasi dengan tabel Plugins
     */
    public function plugins()
    {
        return $this->hasMany(Plugin::class, 'gateway_id');
    }

    /**
     * Relasi dengan tabel Consumers
     */
    public function consumers()
    {
        return $this->hasMany(Consumer::class, 'gateway_id');
    }
}
