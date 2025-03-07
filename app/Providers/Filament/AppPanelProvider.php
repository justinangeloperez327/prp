<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Enums\ThemeMode;
use Filament\FontProviders\GoogleFontProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('app')
            ->path('')
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
            ->defaultThemeMode(ThemeMode::Dark)
            ->brandName('Press Ready Paper')
            ->brandLogoHeight('5rem')
            ->brandLogo(asset('images/PRP-logo-Positive-120x40px.svg'))
            ->darkModeBrandLogo(asset('images/PRP-logo-Negative-120x40px.svg'))
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
            ->plugins([
                FilamentShieldPlugin::make()
                    ->gridColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 3,
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 4,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                    ]),
            ])
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Orders')
                    ->icon('heroicon-o-rectangle-stack')
                    ->collapsible('false'),
                NavigationGroup::make()
                    ->label('Customers')
                    ->icon('heroicon-o-users')
                    ->collapsible('false'),
                NavigationGroup::make()
                    ->label('Products')
                    ->icon('heroicon-o-shopping-bag')
                    ->collapsible('false'),
            ]);
    }
}
