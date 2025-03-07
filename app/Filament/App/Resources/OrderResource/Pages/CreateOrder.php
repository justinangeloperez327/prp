<?php

namespace App\Filament\App\Resources\OrderResource\Pages;

use App\Filament\App\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     $user = Auth::user();

    //     if ($user->hasRole('customer')) {
    //         $data['customer_id'] = $user->contact->customer_id;
    //     }

    //     return $data;
    // }
}
