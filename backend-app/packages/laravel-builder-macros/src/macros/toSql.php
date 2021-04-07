<?php

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

QueryBuilder::macro('toRawSql', function(){
    return array_reduce(
        $this->getBindings(),
        fn($sql, $binding) => preg_replace('/\?/', is_numeric($binding) && !is_string($binding) ? $binding : "'" . $binding . "'", $sql, 1),
        $this->toSql()
    );
});

EloquentBuilder::macro('toRawSql', function(){
    return ($this->getQuery()->toRawSql());
});
