<?php

namespace App\Providers;

use App\Macros\Query\ToRawSql;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Builder::macro('andFilterWhere', function(array $condition) {
            $isEmpty = fn($value) => $value === '' || $value === [] || $value === null || is_string($value) && trim($value) === '';

            $condition = collect($condition)->filter(fn($v) => !$isEmpty($v))->all();

            $condition = collect($condition)->each(function ($v, $k){
                $this->where($k, $v);
            });

            return $this;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
