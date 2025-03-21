<?php

namespace Redot\Datatables;

use Illuminate\Support\ServiceProvider;
use Redot\Datatables\Commands\DatatableMakeCommand;

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
        $this->routes();

        $this->commands([
            DatatableMakeCommand::class,
        ]);
    }

    /**
     * Register the package configuration.
     */
    protected function config(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/datatables.php',
            'datatables'
        );

        $this->publishes([
            __DIR__ . '/../config/datatables.php' => config_path('datatables.php'),
        ], 'datatables::config');
    }

    /**
     * Register the package views.
     */
    protected function views(): void
    {
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            'datatables'
        );

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/datatables'),
        ], 'datatables::views');
    }

    /**
     * Register the package language files.
     */
    protected function lang(): void
    {
        $this->loadTranslationsFrom(
            __DIR__ . '/../lang',
            'datatables'
        );

        $this->publishes([
            __DIR__ . '/../lang' => lang_path('vendor/datatables'),
        ], 'datatables::lang');
    }

    /**
     * Register the package routes.
     */
    protected function routes(): void
    {
        $this->loadRoutesFrom(
            __DIR__ . '/../routes/datatable.php'
        );
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // ...
    }
}
