<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Larabase\Nova\Actions\DownloadExcel;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Timothyasp\Color\Color;

class Loaidat extends Resource
{
    public static $displayInNavigation = false;

    public static $globallySearchable = false;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Loaidat::class;

    public static function group()
    {
        return __('Directory');
    }

    public static function label()
    {
        return __('app.dm_loaidat');
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'ma';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'ma', 'ten'
    ];

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
            Text::make(__('app.st'), 'st')->sortable(),
            Text::make(__('app.ten'), 'ten')->sortable()->rules('required'),
            Text::make(__('app.ma'), 'ma')->sortable()->rules('required'),
            Color::make(__('app.fillColor'), 'fillColor')->rules('required'),
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
        return [
            new DownloadExcel
        ];
    }
}
