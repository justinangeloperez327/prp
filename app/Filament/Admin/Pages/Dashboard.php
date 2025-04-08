<?php

namespace App\Filament\Admin\Pages;

use Illuminate\Support\Facades\Auth;
use Filament\Pages\Dashboard as BasePage;

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
