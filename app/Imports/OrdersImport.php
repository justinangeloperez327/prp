<?php

namespace App\Imports;

use App\Models\Contact;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OrdersImport implements ToCollection, WithBatchInserts, WithChunkReading, WithHeadingRow
{
    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $contact = Contact::where('contact_code', $row['contactcode'])->first();
            if ($contact) {
                DB::table('orders')->updateOrInsert([
                    'order_no' => $row['orderno'],
                ], [
                    'contact_id' => $contact->id,
                    'customer_id' => $contact->customer_id,
                    'order_time' => date('H:i:s', strtotime($row['ordertime'])),
                    'order_date' => date('Y-m-d', strtotime($row['orderdate'])),
                    'would_like_it_by' => date('Y-m-d', strtotime($row['requiredby'])),
                    'dispatch_date' => $row['despatchdate'] ? date('Y-m-d', strtotime($row['despatchdate'])) : null,
                    'status' => $this->generateStatus($row['status']),
                    'purchase_order_no' => $row['ponumber'],
                    'grand_total' => $row['totalexgst'] ? $row['totalexgst'] : 0,
                ]);
            }
        }
    }

    private function generateStatus($status)
    {
        $status = strtolower($status);

        if ($status === 'draft order') {
            return 'draft';
        }

        if ($status === 'new order') {
            return 'new';
        }

        if ($status === 'overdue order') {
            return 'overdue';
        }

        if ($status === 'onhold order') {
            return 'on-hold';
        }

        if ($status === 'on-hold order') {
            return 'on-hold';
        }

        if (! $status) {
            return 'draft';
        }

        return $status;
    }
}
