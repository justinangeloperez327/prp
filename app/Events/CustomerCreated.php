<?php

namespace App\Events;

use App\Models\Customer;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Customer $customer)
    {
        //
    }
}
