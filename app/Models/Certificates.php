<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificates extends Model
{
    use HasFactory;

    protected $table = 'certificates';
    protected $fillable = [
        'cert',
        'key',
        'cert_alt',
        'key_alt',
        'tags',
    ];
}
