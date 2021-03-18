<?php
namespace Larabase\Nova\Fields;


class Date extends \Laravel\Nova\Fields\Date
{
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->format('DD/MM/YYYY');
        $this->pickerDisplayFormat('d/m/Y');
        $this->nullable();
    }

}
