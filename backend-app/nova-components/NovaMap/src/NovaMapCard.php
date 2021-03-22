<?php

namespace Larabase\Nova\Map;

use Laravel\Nova\Card;

class NovaMapCard extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = '1/3';

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'nova-map-card';
    }

    public function configUrl($url){
        return $this->withMeta([
            'configUrl' => $url
        ]);
    }

    public function style($style)
    {
        return $this->withMeta([
            'style' => $style
        ]);
    }

    public function height($height)
    {
        return $this->withMeta([
            'height' => $height
        ]);
    }

    public function center($center)
    {
        return $this->withMeta([
            'center' => $center,
        ]);
    }

    public function zoom($zoom)
    {
        return $this->withMeta([
            'zoom' => $zoom
        ]);
    }


}
