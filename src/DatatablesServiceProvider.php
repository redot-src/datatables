<?php

namespace Redot\Datatables;

use Illuminate\Support\ServiceProvider;

class DatatablesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->config();
        $this->views();
        $this->lang();
    }

    /**
     * Register the package configuration.
     */
    protected function config(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/datatables.php',
            'datatables'
        );

        $this->publishes([
            __DIR__.'/../config/datatables.php' => config_path('datatables.php'),
        ], 'config');
    }

    /**
     * Register the package views.
     */
    protected function views(): void
    {
        $this->loadViewsFrom(
            __DIR__.'/../resources/components',
            'datatables'
        );

        $this->publishes([
            __DIR__.'/../resources/components' => resource_path('components/vendor/datatables'),
        ], 'components');
    }

    /**
     * Register the package language files.
     */
    protected function lang(): void
    {
        $this->loadTranslationsFrom(
            __DIR__.'/../lang',
            'datatables'
        );

        $this->publishes([
            __DIR__.'/../lang' => resource_path('lang/vendor/datatables'),
        ], 'lang');
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // ...
    }
}
