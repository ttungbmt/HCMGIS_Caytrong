<?php
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Arr;

QueryBuilder::macro('whereIntersection', function($column, $geom){
    return $this->whereRaw("ST_Intersects({$column}, ST_GeomFromGeoJSON('{$geom}'))");
});

EloquentBuilder::macro('whereIntersection', function($column, $geom){
    return ($this->getQuery()->whereIntersection(...func_get_args()));
});

