<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upstream extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = ['name','host_header','client_certificate','tags','algorithm','slots','has_on','hash_fallback','health_check','healthchecks_threshold'];
}
