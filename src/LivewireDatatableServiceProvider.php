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
            __DIR__.'/../resources/views' => resource_path('views/vendor/livewire-datatable'),
        ], 'views');

        $this->loadJsonTranslationsFrom(__DIR__.'/../lang');

        $this->publishes([
            __DIR__.'/../lang' => resource_path('lang/vendor/livewire-datatable'),
        ], 'lang');

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
