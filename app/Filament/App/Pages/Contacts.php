<?php

namespace App\Filament\App\Pages;

use App\Models\Contact;
use Filament\Pages\Page;

class Contacts extends Page
{

    protected static ?string $navigationGroup = 'Customers';

    protected static ?string $navigationLabel = 'Contact List';

    public static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return Contact::count();
    }

    protected static string $view = 'filament.app.pages.contacts';
}
