<?php

namespace App\Enums;

enum DeliveryChargeTypes: string
{
    case FIXED = 'fixed';
    case MINIMUM = 'minimum-order';
    case NONE = 'none';
}
