<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'customer_no',
        'phone',
        'email',
        'fax',
        'website',
        'status',
        'street',
        'city',
        'state',
        'postcode',
        'apply_delivery_charge',
        'delivery_charge',
        'charge_trigger',
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    // public function getApplyDeliveryChargeAttribute($value)
    // {
    //     return match ($value) {
    //         'none' => 'None',
    //         'fixed' => 'Fixed',
    //         'minimum-order' => 'Minimum Order',
    //         default => $value,
    //     };
    // }
}
