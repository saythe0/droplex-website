<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'method',
        'amount',
        'user_id',
        'data',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'data' => 'json',
    ];
}
