<?php

namespace App\Services\MYOB\Sales\Invoices\Items;

use Exception;

class NewItemInvoice
{
    public string $number;

    public string $date;

    public ?string $supplierInvoiceNumber;

    public array $customer;

    public string $shipToAddress;

    public ?array $terms;

    public ?bool $isTaxInclusive;

    public ?bool $isReportable;

    public array $lines;

    public ?float $subtotal;

    public ?float $freight;

    public ?array $freightTaxCode;

    public ?float $totalTax;

    public float $totalAmount;

    public ?string $journalMemo;

    public string $billDeliveryStatus;

    public float $balanceDueAmount;

    public string $status;

    public function __construct(array $data)
    {
        $this->number = $data['Number'];
        $this->date = $data['Date'];
        $this->supplierInvoiceNumber = $data['SupplierInvoiceNumber'] ?? null;
        $this->customer = $data['Customer'];
        $this->shipToAddress = $data['ShipToAddress'];
        $this->terms = $data['Terms'];
        $this->isTaxInclusive = $data['IsTaxInclusive'];
        $this->isReportable = $data['IsReportable'];
        $this->lines = $data['Lines'];
        $this->subtotal = $data['Subtotal'];
        $this->freight = $data['Freight'];
        $this->freightTaxCode = $data['FreightTaxCode'];
        $this->totalTax = $data['TotalTax'];
        $this->totalAmount = $data['TotalAmount'];
        $this->journalMemo = $data['JournalMemo'] ?? null;
        $this->billDeliveryStatus = $data['BillDeliveryStatus'];
        $this->balanceDueAmount = $data['BalanceDueAmount'];
        $this->status = $data['Status'];
    }

    /**
     * Validate the invoice data.
     *
     * @throws Exception
     */
    public function validate(): void
    {
        if (empty($this->number)) {
            throw new Exception('Invoice number is required.');
        }

        if (empty($this->date)) {
            throw new Exception('Invoice date is required.');
        }

        if (empty($this->customer['UID'])) {
            throw new Exception('Customer UID is required.');
        }

        if (empty($this->lines) || ! is_array($this->lines)) {
            throw new Exception('Invoice lines are required.');
        }
    }

    /**
     * Convert the object to an array for API requests.
     */
    public function toArray(): array
    {
        return [
            'Number' => $this->number,
            'Date' => $this->date,
            'SupplierInvoiceNumber' => $this->supplierInvoiceNumber,
            'Customer' => $this->customer,
            'ShipToAddress' => $this->shipToAddress,
            'Terms' => $this->terms,
            'IsTaxInclusive' => $this->isTaxInclusive,
            'IsReportable' => $this->isReportable,
            'Lines' => $this->lines,
            'Subtotal' => $this->subtotal,
            'Freight' => $this->freight,
            'FreightTaxCode' => $this->freightTaxCode,
            'TotalTax' => $this->totalTax,
            'TotalAmount' => $this->totalAmount,
            'JournalMemo' => $this->journalMemo,
            'BillDeliveryStatus' => $this->billDeliveryStatus,
            'BalanceDueAmount' => $this->balanceDueAmount,
            'Status' => $this->status,
        ];
    }
}
