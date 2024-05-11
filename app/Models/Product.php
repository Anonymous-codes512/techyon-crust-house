<?php

namespace App\Models;

use App\Models\Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function deals()
    {
        return $this->belongsToMany(Deal::class, 'deal_product')
            ->withPivot('product_quantity', 'product_total_price');
    }
}
