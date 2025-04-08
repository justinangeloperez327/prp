<?php

namespace App\Listeners;

use App\Events\OrderUpdated;
use App\Notifications\OrderUpdatedNotification;
use Illuminate\Support\Facades\Notification;

class SendOrderUpdatedNotification
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
    public function handle(OrderUpdated $event): void
    {
        $order = $event->order;
        if ($order->customer && $order->customer->email) {
            Notification::route('mail', $order->customer->email)
                ->notify(new OrderUpdatedNotification($order));
        }
    }
}
