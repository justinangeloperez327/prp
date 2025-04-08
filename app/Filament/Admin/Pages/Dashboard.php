<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BasePage;
use Illuminate\Support\Facades\Auth;

class Dashboard extends BasePage
{
    public static function canAccess(): bool
    {
        if (Auth::user()->hasRole('customer')) {
            return false;
        }

        return parent::canAccess();
    }
}
