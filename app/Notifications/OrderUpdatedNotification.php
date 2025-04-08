<?php

namespace App\Notifications;

use App\Mail\OrderUpdated as OrderUpdatedMailable;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class OrderUpdatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Order $order)
    {
        $this->order->load('items.product');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): Mailable
    {
        return new OrderUpdatedMailable($this->order);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    private function getOrderItems(): Collection
    {
        return $this->order->items->map(function ($item) {
            return [
                'product' => $item->product->name.' '.$item->product_size.($item->product_colour ? ' - '.$item->product_colour : ''),
                'instructions' => $item->special_instructions,
                'quantity' => $item->quantity,
                'total' => $item->total,
            ];
        });

    }
}
