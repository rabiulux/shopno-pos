<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'unit',
        'quantity',
        'image',
        'category_id',
        'user_id',
    ];
    protected $casts = [
        'price' => 'decimal:2',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stock()
    {
        return $this->hasOne(ProductStock::class);
    }
}
