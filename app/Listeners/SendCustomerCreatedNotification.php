<?php

namespace App\Listeners;

use App\Events\CustomerCreated;
use App\Notifications\CustomerCreatedNotification;
use Illuminate\Support\Facades\Notification;

class SendCustomerCreatedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CustomerCreated $event): void
    {
        $customer = $event->customer;
        Notification::route('mail', $customer->email)->notify(new CustomerCreatedNotification($customer));
    }
}
