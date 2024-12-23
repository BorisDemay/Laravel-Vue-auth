<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Checkout extends Model
{
    use HasFactory;

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

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
