<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PluginServiceRoute extends Pivot
{
    use HasFactory;
    use HasUuids;

    protected $table        = 'plugin_service_route';
    protected $guarded      = ['created_at','updated_at'];
    protected $keyType      = 'string';
    public $incrementing    = false;
}
