<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PluginType extends Model
{
    use HasFactory;
    use HasUuids;
    
    protected $table = 'plugin_types';
    protected $fillable = ['id', 'name', 'config'];
}
