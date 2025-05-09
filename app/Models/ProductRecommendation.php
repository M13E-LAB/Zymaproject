<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRecommendation extends Model
{
    protected $fillable = [
        'product_id',
        'health_score',
        'price_score',
        'final_score',
        'average_price',
        'barcode',
        'brand',
        'name'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
} 