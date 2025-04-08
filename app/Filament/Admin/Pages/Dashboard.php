<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BasePage;

class Dashboard extends BasePage
{
    public static function canAccess(): bool
    {
        if (auth()->user()->hasRole('customer')) {
            return false;
        }

        return parent::canAccess();
    }
}
