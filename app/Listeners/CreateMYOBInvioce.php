<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\Invoice;
use App\Services\MYOB\Client;
use App\Services\MYOB\Sales\Invoices\Items\CreateNewItemInvoice;
use App\Services\MYOB\Sales\Invoices\Items\NewItemInvoice;
use Exception;
use Illuminate\Support\Facades\Log;

class CreateMYOBInvioce
{
    public function handle(OrderCreated $event)
    {
        $order = $event->order;

        Invoice::create([
            'order_id' => $order->id,
            'amount' => $order->grand_total,
            'status' => 'pending',
        ]);

        try {
            $client = new Client;

            $invoiceData = new NewItemInvoice([
                'Number' => $order->id,
                'Date' => now()->toISOString(),
                'Customer' => [
                    'UID' => $order->customer->customer_code,
                ],
                'ShipToAddress' => $order->customer->full_address,
                'Lines' => $order->items->map(function ($item) {
                    return [
                        'Type' => 'Transaction',
                        // 'Description' => $item->description,
                        'BillQuantity' => $item->quantity,
                        'UnitPrice' => $item->productItem->price_per_quantity,
                        'Total' => $item->total,
                        'Item' => [
                            'UID' => $item->product_id,
                        ],
                    ];
                })->toArray(),
                'TotalAmount' => $order->grand_total,
                'BillDeliveryStatus' => 'Print',
                'Status' => 'Open',
            ]);

            // Use CreateNewItemInvoice to send the invoice to MYOB
            $createInvoiceService = new CreateNewItemInvoice($client);
            $createInvoiceService->handle($invoiceData);
        } catch (Exception $e) {
            // Log the exception or handle it as needed
            Log::error('Failed to create MYOB invoice: '.$e->getMessage());
        }
    }
}
