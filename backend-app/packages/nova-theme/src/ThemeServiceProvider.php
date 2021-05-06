<?php

namespace Larabase\NovaTheme;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class ThemeServiceProvider extends ServiceProvider
{
    const NOVA_VIEWS_PATH = __DIR__ . '/../resources/views';
    const CSS_PATH = __DIR__ . '/../resources/css';
    const JS_PATH = __DIR__ . '/../resources/js';
    const CONFIG_FILE = __DIR__ . '/../config/nova-theme.php';

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nova-theme');

        Nova::serving(function (ServingNova $event) {
            Nova::style('nova-theme', __DIR__ . '/../dist/css/theme.css');
            Nova::script('nova-theme', __DIR__ . '/../dist/js/theme.js');

            Nova::provideToScript([
                'nt' => config('nova-theme')
            ]);
        });

        // Publishes Config
        $this->publishes([
            self::CONFIG_FILE => config_path('nova-theme.php'),
        ], 'config');

        // Views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'nova-theme');

        // Publish Public Assets
        $this->publishes([
            self::CSS_PATH => public_path('vendor/larabase/nova-theme'),
            self::JS_PATH => public_path('vendor/larabase/nova-theme'),
        ], 'nova-theme-public');

        // Sets CSS file as asset
//        Nova::theme(asset('vendor/larabase/nova-theme/theme.css'));
//        Nova::theme(asset('vendor/larabase/nova-theme/theme.js'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_FILE,
            'nova-theme'
        );
    }
}
