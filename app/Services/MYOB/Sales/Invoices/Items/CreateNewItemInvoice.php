<?php

namespace App\Services\MYOB\Sales\Invoices\Items;

use App\Services\MYOB\Client;
use Exception;

class CreateNewItemInvoice
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Create a new item invoice in MYOB.
     *
     * @throws Exception
     */
    public function handle(NewItemInvoice $invoice): array
    {
        try {
            // Validate the invoice object
            $invoice->validate();

            // Send request to MYOB API
            $response = $this->client->post('/Sale/Invoice/Item', $invoice->toArray());

            return $response;
        } catch (Exception $e) {
            // Handle exceptions
            throw new Exception('Failed to create item invoice: '.$e->getMessage());
        }
    }
}
