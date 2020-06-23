<?php

namespace IvanoMatteo\LaravelJsBindings;

use Illuminate\Support\ServiceProvider;
use IvanoMatteo\LaravelJsBindings\Commands\Constants;
use IvanoMatteo\LaravelJsBindings\Commands\Lang;
use IvanoMatteo\LaravelJsBindings\Commands\Routes;

class LaravelJsBindingsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-js-bindings');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-js-bindings');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('jsbindings.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-js-bindings'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-js-bindings'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-js-bindings'),
            ], 'lang');*/

            // Registering package commands.
            $this->commands([
                Constants::class,
                Routes::class,
                Lang::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'jsbindings');

        // Register the main class to use with the facade
        //$this->app->singleton('laravel-js-bindings', function () {
        //    return new LaravelJsBindings;
        //});
    }
}
