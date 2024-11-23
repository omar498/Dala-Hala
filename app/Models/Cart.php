<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'consumer_id',
        'product_id',
        'quantity',

    ];
    public function consumer()
    {
        return $this->belongsTo(Consumer::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
