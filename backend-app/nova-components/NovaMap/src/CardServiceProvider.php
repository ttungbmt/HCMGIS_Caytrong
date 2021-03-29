<?php

namespace Larabase\Nova\Map;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class CardServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->booted(function () {
            $this->routes();
        });

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'nova-map');

        Nova::serving(function (ServingNova $event) {
            Nova::provideToScript([
                'map4dApiKey' => config('services.map4d.key'),
            ]);

            Nova::style('leaflet', 'https://cdn.jsdelivr.net/npm/leaflet@1.0.3/dist/leaflet.css');

            Nova::script('nova-map', __DIR__.'/../dist/js/tool.js');
            Nova::style('nova-map', __DIR__.'/../dist/css/tool.css');
        });

        $this->publishes([
            __DIR__ . '/../dist/images' => public_path('images'),
            __DIR__.'/../config/nova-map.php' => config_path('nova-map.php'),
        ], 'nova-map');
    }

    /**
     * Register the card's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova'])
                ->prefix('nova-vendor/nova-map')
                ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/nova-map.php', 'nova-map');
    }
}
