<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    /** @use HasFactory<\Database\Factories\ProductCategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'category_uid',
        'name',
        'status',
        'order',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
