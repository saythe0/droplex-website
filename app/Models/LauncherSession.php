<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LauncherSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'user_uuid',
        'access_token',
        'refresh_token',
        'server_id',
        'expire_in',
        'created_at',
        'updated_at',
    ];

}
