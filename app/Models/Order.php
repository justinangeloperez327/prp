<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_no',
        'customer_id',
        'order_date',
        'order_time',
        'would_like_it_by',
        'status',
        'additional_instructions',
        'grand_total',
        'purchase_order_no',
        'dispatch_date',
        'delivery_charge',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'date',
            'would_like_it_by' => 'date',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
