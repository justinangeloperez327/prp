<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderCreatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Order $order)
    {
        //
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
    public function toMail(object $notifiable): MailMessage
    {
        // dd($notifiable);
        return (new MailMessage)
            ->subject('Your Order Has Been Created')
            ->greeting('Hello ' . $this->order->customer->email . ',')
            ->line('We are pleased to inform you that your order has been successfully created.')
            ->line('Order Number: ' . $this->order->order_no)
            ->line('Order Date: ' . $this->order->created_at->format('Y-m-d'))
            ->action('View Order', url('/p/orders/' . $this->order->id))
            ->line('Thank you for choosing our service!');
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
}
