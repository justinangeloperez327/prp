<?php

namespace App\Filament\Admin\Resources\OrderResource\Pages;

use App\Filament\Admin\Resources\OrderResource;
use App\Models\Invoice;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    // TODO generate invoice
    // when creating order I want to generate invoice
    protected function afterCreate(): void
    {
        // Generate an invoice after creating the order
        $order = $this->record;

        // Assuming you have an Invoice model and logic to create an invoice
        Invoice::create([
            'order_id' => $order->id,
            'amount' => $order->total_amount, // Adjust based on your order structure
            'status' => 'pending',
        ]);
    }
}
