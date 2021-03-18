<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

class HcQuan extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\HcQuan::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'tenquan';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'maquan', 'tenquan',
    ];

    public static function group()
    {
        return __('Directory');
    }

    public static function label()
    {
        return __('app.hc_quan');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Text::make(__('app.maquan'), 'maquan')->sortable()->rules(['required', 'unique:'.self::$model]),
            Text::make(__('app.tenquan'), 'tenquan')->sortable()->rules(['required', 'unique:'.self::$model]),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
