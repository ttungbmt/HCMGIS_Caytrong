<?php

namespace Larabase\Nova;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\NovaServiceProviderRegistered;

class NovaCoreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerResources();

        $this->app['events']->listen(NovaServiceProviderRegistered::class, function ($event) {
            $this->app->register(NovaServiceProvider::class);
        });
    }

    /**
     * Register the package resources such as routes, templates, etc.
     *
     * @return void
     */
    protected function registerResources()
    {
        $this->loadJsonTranslationsFrom(__DIR__ . '/../resources/lang');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../resources/lang/vendor/filemanager');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}