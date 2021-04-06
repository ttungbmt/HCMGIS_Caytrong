<?php

namespace App\Nova;

use App\Nova\Fields\Place;
use App\Nova\Filters\PhuongFilter;
use App\Nova\Filters\QuanFilter;
use App\Support\Helper;
use Larabase\Nova\Cards\FiltersSummary;
use Illuminate\Http\Request;
use Larabase\Nova\Map\Fields\Map;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

class KdThuocBVTV extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\KdThuocBVTV::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'ten';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'ten', 'diachi'
    ];

    public static function group()
    {
        return __('app.caytrong');
    }

    public static function label()
    {
        return __('app.kd_thuoc_bvtv');
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
            Map::make(__('Map'), 'geom'),
            ...Helper::hcFields(),
            Text::make(__('app.ten'), 'ten'),
            Place::make(__('app.diachi'), 'diachi'),
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
        return [
            FiltersSummary::make(),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            QuanFilter::make(),
            PhuongFilter::make(),
        ];
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
