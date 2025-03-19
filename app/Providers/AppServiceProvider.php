<?php

namespace App\Providers;

use App\Console\Commands\DataSeed;
use App\Providers\Filament\AdminPanelProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerCommands();
        $this->app->register(AdminPanelProvider::class);
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
            DataSeed::class,
        ]);
    }
}
