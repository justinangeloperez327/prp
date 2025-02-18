<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'description',
        'type_list',
        'colour_list',
        'status',
        'product_category_id',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(ProductItem::class);
    }
}
