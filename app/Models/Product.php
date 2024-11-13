<?php

namespace App\Models;

use App\Models\Categories;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'price', 'stock', 'category_id'];

    public function category()
    {
       //  return $this->belongsTo(Categories::class);
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);

    }

}
