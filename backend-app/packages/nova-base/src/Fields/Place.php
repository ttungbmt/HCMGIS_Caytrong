<?php
namespace Larabase\Nova\Fields;

class Place extends \Laravel\Nova\Fields\Place
{
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $locale = config('app.locale');
        if($locale !== 'en') {
            $this->countries(['VN']);
            $this->language($locale);
        }
    }
}
