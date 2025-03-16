<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Enums\ThemeMode;
use Filament\Support\Colors\Color;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Admin\Widgets\OrdersChart;
use Filament\FontProviders\GoogleFontProvider;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('/p')
            ->login()
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Indigo,
                'primary' => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                'rose' => Color::Rose,
                'lime' => Color::Lime,
                'amber' => Color::Amber,
                'emerald' => Color::Emerald,
                'indigo' => Color::Indigo,
                'blue' => Color::Blue,
                'orange' => Color::Orange,
                'red' => Color::Red,
                'yellow' => Color::Yellow,
                'green' => Color::Green,
                'slate' => Color::Slate,
                'pink' => Color::Pink,
                'cyan' => Color::Cyan,
                'purple' => Color::Purple,
                'teal' => Color::Teal,
            ])
            ->font('Poppins')
            ->font('Inter', provider: GoogleFontProvider::class)
            ->defaultThemeMode(ThemeMode::Light)
            ->brandName('Press Ready Paper')
            ->brandLogoHeight('5rem')
            ->brandLogo(asset('images/PRP-logo-Positive-120x40px.svg'))
            ->darkModeBrandLogo(asset('images/PRP-logo-Negative-120x40px.svg'))
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                OrdersChart::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->default();
    }
}
