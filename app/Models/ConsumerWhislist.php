<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsumerWhislist extends Model
{
    use SoftDeletes;

    protected $table = 'consumer_products';

    protected $fillable = [
        'consumer_id',
        'product_id',
        // Add any additional columns as needed, e.g., quantity, color, etc.
    ];
    // Define relationships if needed
    public function consumer()
    {
        return $this->belongsTo(Consumer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
