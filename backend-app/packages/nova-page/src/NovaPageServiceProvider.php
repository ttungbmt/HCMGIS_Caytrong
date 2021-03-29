<?php

namespace Larabase\NovaPage;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Larabase\NovaPage\Http\Middleware\Authorize;
use Larabase\NovaPage\Http\Middleware\PagePathExists;
use OptimistDigital\NovaTranslationsLoader\LoadsNovaTranslations;

class NovaPageServiceProvider extends ServiceProvider
{
    use LoadsNovaTranslations;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nova-page');

        if ($this->app->runningInConsole()) {
            // Publish config
            $this->publishes([
                __DIR__ . '/../config/' => config_path(),
            ], 'config');
        }
    }

    public function register()
    {
        $this->registerRoutes();

        $this->mergeConfigFrom(
            __DIR__ . '/../config/nova-page.php',
            'nova-page'
        );
    }

    protected function registerRoutes()
    {
        if ($this->app->routesAreCached()) return;

        Route::middleware(['nova', Authorize::class, PagePathExists::class])
            ->group(__DIR__ . '/../routes/api.php');
    }
}
