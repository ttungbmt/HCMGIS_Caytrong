<?php

namespace Larabase\Nova\Map\Fields;

use Laravel\Nova\Fields\Text;

class Place extends Text
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'place';

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|null  $attribute
     * @param  mixed|null  $resolveCallback
     * @return void
     */
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this
             ->latitude('latitude')
             ->longitude('longitude');
    }

    public function searchProvider(string $provider)
    {
        return $this->withMeta([__FUNCTION__ => $provider]);
    }

    public function searchParams(array $field)
    {
        return $this->withMeta([__FUNCTION__ => $field]);
    }

    public function debounce(string $field)
    {
        return $this->withMeta([__FUNCTION__ => $field]);
    }

    public function searchProviderKey(string $key)
    {
        return $this->withMeta([__FUNCTION__ => $key]);
    }

    /**
     * Specify the field that contains the latitude.
     *
     * @param  string  $field
     * @return $this
     */
    public function latitude($field)
    {
        return $this->withMeta([__FUNCTION__ => $field]);
    }

    /**
     * Specify the language that places.js should use.
     *
     * @param  string  $language
     * @return $this
     */
    public function language($language)
    {
        return $this->withMeta([__FUNCTION__ => $language]);
    }

    /**
     * Specify the field that contains the longitude.
     *
     * @param  string  $field
     * @return $this
     */
    public function longitude($field)
    {
        return $this->withMeta([__FUNCTION__ => $field]);
    }
}
