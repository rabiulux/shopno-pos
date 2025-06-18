<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'user_id',
        'supplier_id',
        'total',
        'discount',
        'vat',
        'payable',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchase_items()
    {
        return $this->hasMany(PurchaseProduct::class);
    }
}
