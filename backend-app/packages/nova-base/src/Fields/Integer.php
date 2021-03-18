<?php

namespace Larabase\Nova\Fields;


use Laravel\Nova\Fields\Number;

class Integer extends Number
{
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->min(0);
        $this->step(1);
    }
}
