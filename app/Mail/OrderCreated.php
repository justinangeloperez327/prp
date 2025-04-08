<?php

namespace App\Mail;

use App\Models\Order;
use Filament\Forms\Components\View;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $items;

    /**
     * Create a new message instance.
     */
    public function __construct(public Order $order)
    {
        $this->order->load('items.product');
        $this->items = $this->order->items->map(function ($item) {
            return [
                'product' => $item->product->name.' '.$item->product_size.($item->product_colour ? ' - '.$item->product_colour : ''),
                'instructions' => $item->special_instructions,
                'quantity' => $item->quantity,
                'total' => $item->total,
            ];
        });
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('omi@design.com', 'OmiDesign'),
            to: [
                new Address($this->order->customer->email),
            ],
            subject: '[New Order] for OmiDesign - '.$this->order->order_no,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-created',
            with: [
                'order' => $this->order,
                'items' => $this->items,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
