<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['name', 'email', 'mobile', 'user_id'];

    /**
     * Get the user that owns the supplier.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
