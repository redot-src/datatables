<?php

namespace Redot\LivewireDatatable;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LivewireDatatableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'livewire-datatable');

        $this->publishes([
            __DIR__.'/../resources/views' => $this->app->resourcePath('views/vendor/livewire-datatable'),
        ], 'livewire-datatable-views');

        $this->loadJsonTranslationsFrom(__DIR__.'/../lang');

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('lang/vendor/livewire-datatable'),
        ], 'livewire-datatable-lang');

        $this->commands([
            Console\MakeDatatableCommand::class,
        ]);

        Livewire::component('livewire-datatable', Datatable::class);
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        //
    }
}
