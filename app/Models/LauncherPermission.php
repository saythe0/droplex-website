<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LauncherPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'user_uuid',
        'permission',
        'created_at',
        'updated_at',
    ];
}
