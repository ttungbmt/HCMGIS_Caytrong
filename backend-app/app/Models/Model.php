<?php

namespace App\Models;


class Model extends \Illuminate\Database\Eloquent\Model
{
    public function scopeAndFilterWhere($query, $condition){
        $condition = collect($condition)->filter(fn($v) => !$this->isEmpty($v))->all();
        return $query->where($condition);
    }

    /**
     * Returns a value indicating whether the give value is "empty".
     *
     * The value is considered "empty", if one of the following conditions is satisfied:
     *
     * - it is `null`,
     * - an empty string (`''`),
     * - a string containing only whitespace characters,
     * - or an empty array.
     *
     * @param mixed $value
     * @return bool if the value is empty
     */
    protected function isEmpty($value)
    {
        return $value === '' || $value === [] || $value === null || is_string($value) && trim($value) === '';
    }
}
