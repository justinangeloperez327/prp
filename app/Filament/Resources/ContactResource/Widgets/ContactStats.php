<?php

namespace App\Filament\Resources\ContactResource\Widgets;

use App\Models\Contact;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContactStats extends BaseWidget
{
    protected ?string $heading = 'Contacts';

    protected function getStats(): array
    {
        return [
            Stat::make('Active Contacts', Contact::where('status', 'active')->count()),
            Stat::make('Inactive Contacts', Contact::where('status', 'inactive')->count()),
        ];
    }
}
