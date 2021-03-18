<?php
namespace Larabase\Nova\Metrics;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Nova;

abstract class Value extends \Laravel\Nova\Metrics\Value
{
    protected function aggregate($request, $model, $function, $column = null, $dateColumn = null)
    {
        $query = $model instanceof Builder ? $model : (new $model)->newQuery();

        $column = $column ?? $query->getModel()->getQualifiedKeyName();

        $timezone = Nova::resolveUserTimezone($request) ?? $request->timezone;

        $fn_calc = function($fn_range) use($query, $request, $timezone, $function, $column){
            $query = with(clone $query)->whereBetween(
                $dateColumn ?? $query->getModel()->getCreatedAtColumn(),
                $this->{$fn_range}($request->range, $timezone)
            );

            if($request->range == -1) $query->orWhereNotNull($column);
            return round($query->{$function}($column), $this->precision);
        };

        $previousValue = $fn_calc('previousRange');
        return $this->result($fn_calc('currentRange'))->previous($previousValue);
    }
}
