<?php

namespace App\Services\MYOB\Inventory;

class ItemTransformer
{
    /**
     * Transform the given item data into the desired format.
     *
     * @param array $item
     * @return array
     */
    public function transform(array $item): array
    {
        return [
            'id' => $item['UID'] ?? null,
            'name' => $item['NAME'] ?? '',
        ];
    }
}
