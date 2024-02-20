<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'code',
        'count',
        'uses',
        'data',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'data' => 'json',
    ];
}
