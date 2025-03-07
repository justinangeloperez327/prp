<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Draft = 'draft';
    case New = 'new';
    case Processed = 'processed';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case OnHold = 'on-hold';
    case Overdue = 'overdue';
}
