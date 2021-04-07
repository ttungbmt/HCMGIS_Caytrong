<?php
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Arr;

QueryBuilder::macro('whereFilter', function($column, $operator = null, $value = null, $boolean = 'and'){
    $isEmpty = fn($value) => $value === '' || $value === [] || $value === null || is_string($value) && trim($value) === '';

    if (is_array($column)) {
        return $this->whereNested(function ($query) use ($isEmpty, $column) {
            foreach ($column as $key => $value) {
                if(is_string($key)){
                    !$isEmpty($value) && $query->where($key, '=', $value);
                } else {
                    $val = count($value) === 2 ? $value[1] : $value[2];
                    if(!Arr::isAssoc($value) && !$isEmpty($val)) $query->where(...$value);
                }
            }
        }, $boolean);
    }

    if(func_num_args() === 3 && $isEmpty($value)) return $this;

    [$value, $operator] = $this->prepareValueAndOperator($value, $operator, func_num_args() === 2);

    !$isEmpty($value) && $this->where($column, $operator, $value);

    return $this;
});

EloquentBuilder::macro('whereFilter', function($column, $operator = null, $value = null, $boolean = 'and'){
    return ($this->getQuery()->whereFilter(...func_get_args()));
});
