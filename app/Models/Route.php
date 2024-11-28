<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;
    use HasUuids;

    protected $filable = ['name', 'service', 'tags'];

    public function service()
    {
        return $this->hasMany(Service::class);
    }

}
