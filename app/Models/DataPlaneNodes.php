<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPlaneNodes extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'data_plane_nodes';

    protected $fillable = [
        'name',
        'description',
        'deployment_models',
        'status',
        'public_key',
        'private_key',
    ];

    public function gatewayServices()
    {
        return $this->hasMany(GatewayService::class, 'data_plane_id');
    }
}
