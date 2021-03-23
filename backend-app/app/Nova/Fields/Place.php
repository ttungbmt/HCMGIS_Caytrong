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
                'location' => '10.78019824098886,106.6882877543809'
            ]);
    }

}
