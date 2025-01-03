<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upstream extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'upstreams';
    protected $fillable = ['name', 'upstream_host', 'upstream_port', 'client_certificate','tags'];
}
