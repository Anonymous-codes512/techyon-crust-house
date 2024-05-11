<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->belongsToMany(Product::class, 'deal_product')
            ->withPivot('product_quantity', 'product_total_price');
    }
}
