<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $casts = [
        'products' => 'array',
    ];

    protected $fillable = ['total_bill_amount', 'products'];
}
