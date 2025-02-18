<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'title',
        'first_name',
        'last_name',
        'contact_no',
        'customer_id',
        'email',
        'direct_phone',
        'mobile_phone',
        'status',
        'username',
        'password',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
