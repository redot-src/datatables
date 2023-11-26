<?php

namespace Redot\LivewireDatatable;

use Illuminate\Support\ServiceProvider;

class LivewireDatatableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'livewire-datatable');
        $this->mergeConfigFrom(__DIR__.'/../config/livewire-datatable.php', 'livewire-datatable');

        $this->publishes([
            __DIR__.'/../resources/views' => $this->app->resourcePath('views/vendor/livewire-datatable'),
        ], 'livewire-datatable-views');

        $this->publishes([
            __DIR__.'/../config/livewire-datatable.php' => $this->app->configPath('livewire-datatable.php'),
        ], 'livewire-datatable-config');

        $this->commands([
            Console\MakeDatatableCommand::class,
        ]);
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        //
    }
}
