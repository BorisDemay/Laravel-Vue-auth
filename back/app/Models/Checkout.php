<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    protected $fillable = [
        'session_id',
        'products',
        'status',
        'client_email',
        'order_reference',
    ];

    protected $casts = [
        'products' => 'array',
    ];
}
