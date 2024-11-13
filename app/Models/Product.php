<?php

namespace App\Models;

use App\Models\Categories;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'price', 'stock', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }
    public function cart()
    {
        return $this->belongsTo(Cart::class);

    }
    // public function consumer()
    // {
    //     return $this->belongsTo(Consumer::class);
    // }
}
