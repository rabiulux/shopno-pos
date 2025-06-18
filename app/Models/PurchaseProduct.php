<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseProduct extends Model
{
    protected $fillable = [
        'purchase_id',
        'user_id',
        'product_id',
        'quantity',
        'purchase_price',
    ];

    /**
     * Get the purchase that owns the product.
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'id', 'product_id');
    }
}
