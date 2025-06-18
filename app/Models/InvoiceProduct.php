<?php

namespace App\Models;

use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{
    protected $fillable = [
        'invoice_id',
        'user_id',
        'product_id',
        'quantity',
        'sale_price',
    ];

    /**
     * Get the invoice that owns the product.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function products()
    {
        return $this->hasMany((Product::class), 'id', 'product_id');
    }
}
