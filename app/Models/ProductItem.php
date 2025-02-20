<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductItem extends Model
{
    protected $fillable = [
        'size',
        'unit',
        'quantity',
        'gsm',

        'sheets_per_mill_pack',
        'sheets_per_pallet',

        'price_per_quantity',
        'price_broken_mill_pack',

        'status',
        'product_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
