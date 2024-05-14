<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Deal extends Model
{
    use HasFactory;

    public function handlers()
    {
        return $this->hasMany(Handler::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'handlers')->withPivot('product_quantity', 'product_total_price');
    }
}
