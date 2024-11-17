<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Rate;
use App\Models\Categories;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'price', 'stock', 'category_id','image_path'];

    public function category()
    {
       //  return $this->belongsTo(Categories::class);
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function rates()
    {
        return $this->hasMany(Rate::class);
    }

    public function averageRating()
    {
        return $this->rates()->avg('rate'); // Assuming 'score' is the column for the rating value
    }

}
