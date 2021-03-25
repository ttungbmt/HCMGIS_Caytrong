<?php
namespace App\Nova\Fields;

class Place extends \Larabase\Nova\Map\Fields\Place
{
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this
            ->searchProvider('map4d')
            ->searchParams([
                'location' => collect(config('nova-map.config.center'))->implode(',')
            ]);
    }

}
