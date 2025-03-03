<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'order_no',
        'user_id',
        'order_date',
        'order_time',
        'would_like_it_by',
        // 'due_date',
        'status',
        'additional_instructions',
        'delivery_charge',
        'total',
        'purchase_order_no',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
