<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_code',
        'company',
        'phone',
        'email',
        'fax',
        'website',
        'status',
        'street',
        'city',
        'state',
        'postcode',
        'country',
        'apply_delivery_charge',
        'delivery_charge',
        'charge_trigger',
        'active',
        'notes',
    ];

    // I want full address here
    public function getFullAddressAttribute(): string
    {
        return "{$this->street}, {$this->city}, {$this->state}, {$this->postcode}";
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
