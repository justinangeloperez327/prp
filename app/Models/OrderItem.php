<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_category_id',
        'product_id',
        'product_item_id',
        'product_colour',
        'quantity',
        'total',
        'special_instructions',
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
