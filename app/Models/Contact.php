<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'contact_code',
        'customer_id',
        'user_id',
        'first_name',
        'last_name',
        'title',
        'email',
        'direct_phone',
        'mobile_phone',
        'notes',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
