<?php

namespace App\Filament\Admin\Resources\OrderResource\Pages;

use App\Models\Order;
use App\Models\ProductItem;
use App\Events\OrderCreated;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Admin\Resources\OrderResource;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (Auth::user()->hasRole('customer')) {
            $data['customer_id'] = Auth::user()->contact->customer_id;
        }

        $data['status'] = 'new';
        $data['order_no'] = $this->generateOrderNo();
        $data['delivery_charge'] = $data['applied_delivery_charge'];

        return $data;
    }

    protected function handleRecordCreation(array $data): Order
    {
        $order = static::getModel()::create($data);

        $itemsData = $this->form->getRawState()['items'] ?? [];

        foreach ($itemsData as $item) {
            $items[] = [
                'order_id' => $order->id,
                'product_category_id' => $item['product_category_id'],
                'product_id' => $item['product_id'],
                'product_item_id' => $item['product_item_id'],
                'product_colour' => $item['product_colour'],
                'quantity' => $item['quantity'],
                'total' => $item['total'],
                'special_instructions' => $item['special_instructions'],
            ];
        }

        if (isset($items)) {
            $order->items()->createMany($items);
        }

        return $order;
    }

    protected function afterCreate(): void
    {
        $order = $this->record;

        OrderCreated::dispatch($order);
    }

    private function generateOrderNo(): int
    {
        $order = Order::query()
            ->whereNotNull('order_no')
            ->orderBy('id', 'desc')
            ->orderBy('order_no', 'desc')
            ->first();

        if ($order) {
            $lastOrderNo = $order->order_no;
            $lastOrderNo = $lastOrderNo + 1;

            return $lastOrderNo;
        }

        return 1;
    }
}
