<?php
namespace Larabase\Nova\Cards;

class FiltersSummary extends \Degecko\NovaFiltersSummary\FiltersSummary
{
    public function __construct($component = null)
    {
        parent::__construct($component);

        $this->labels = [
            'active' => '',
            'filter' => function_exists('__') ? __('filter') : 'filter',
            'filters' => function_exists('__') ? __('filters') : 'filters',
        ];
    }
}
