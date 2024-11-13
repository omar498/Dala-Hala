<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'consumer_id',
        'product_id',
        'quantity',
    ];
    public function consumer()
    {
        return $this->belongsTo(Consumer::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);

    }
}
