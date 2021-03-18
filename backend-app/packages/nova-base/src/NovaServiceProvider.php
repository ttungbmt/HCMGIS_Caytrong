<?php

namespace Larabase\Nova;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Larabase\Nova\Listeners\BanEventSubscriber;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Laravel\Nova\Panel;

class NovaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPanelMacros();
        $this->registerJsonVariables();

        // Register event subscribers
        Event::subscribe(BanEventSubscriber::class);
    }

    /**
     * Register the Nova JSON variables.
     *
     * @return void
     */
    protected function registerJsonVariables()
    {
        Nova::serving(function (ServingNova $event) {
            $locale = app()->getLocale();

            Nova::translations(__DIR__ . '/../resources/lang/' . $locale . '.json');
            Nova::translations(__DIR__ . '/../resources/lang/vendor/filemanager/' . $locale . '.json');
            Nova::translations(__DIR__ . '/../resources/lang/vendor/nova-password-reset/' . $locale . '.json');

            Nova::provideToScript([
                'translations' => Nova::allTranslations(),
            ]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    protected function registerPanelMacros()
    {
        $methods = [
            'showOnIndex',
            'showOnDetail',
            'showOnCreating',
            'showOnUpdating',
            'hideFromIndex',
            'hideFromDetail',
            'hideWhenCreating',
            'hideWhenUpdating',
            'onlyOnIndex',
            'onlyOnDetail',
            'onlyOnForms',
            'exceptOnForms',
        ];

        foreach ($methods as $method){
            Panel::macro($method, function () use ($method){
                $this->data = array_map(fn($field) => $field->{$method}() ,$this->data);
                return $this;
            });
        }
    }
}