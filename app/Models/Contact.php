<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'customer_id',
        'email',
        'phone',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
