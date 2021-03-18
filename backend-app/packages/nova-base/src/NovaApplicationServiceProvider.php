<?php
namespace Larabase\Nova;

use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Nova;

class NovaApplicationServiceProvider extends \Laravel\Nova\NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();


        $this->app->booted(function () {
            /*
            Field::macro('readonlyOnUpdate', function ($options = []) {

            });
            */
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
