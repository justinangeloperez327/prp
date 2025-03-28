<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_category_id',
        'product_id',
        'product_item_id',
        'product_size',
        'product_colour',
        'quantity',
        'total',
        'special_instructions',
        'status',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function productItem()
    {
        return $this->belongsTo(ProductItem::class);
    }
}
