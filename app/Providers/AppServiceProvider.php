<?php

namespace App\Providers;

use App\Providers\Filament\AppPanelProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerCommands();
        $this->app->register(AppPanelProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->commands([
            \App\Console\Commands\AdminSeed::class,
            \App\Console\Commands\ProductSeed::class,
        ]);
    }
}
