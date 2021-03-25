<?php

namespace App\Nova;

use App\Support\Helper;
use Illuminate\Http\Request;
use Larabase\Nova\Map\Fields\Map;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Ranhthua extends Resource
{
    public static $tableStyle = 'tight';
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Ranhthua::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'kh_2003',
    ];

    public static function group()
    {
        return __('app.caytrong');
    }

    public static function label()
    {
        return __('app.pg_ranhthua');
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
            BelongsTo::make(__('app.maphuong'), 'phuong', 'App\Nova\HcPhuong'),
            Number::make(__('app.mathua'), 'mathua')->hideFromIndex(),
            Number::make(__('app.maphuong'), 'maphuong')->hideFromIndex(),
            Number::make(__('app.sh_bando'), 'sh_bando'),
            Number::make(__('app.sh_thua'), 'sh_thua'),
            Text::make(__('app.kh_2003'), 'kh_2003'),
            Number::make(__('app.dt_thocu'), 'dt_thocu')->hideFromIndex(),
            Number::make(__('app.dt_phaply'), 'dt_phaply')->hideFromIndex(),
            Text::make(__('app.ma_ld'), 'ma_ld')->hideFromIndex(),
            Text::make(__('app.kihieu_ld'), 'ma_ld')->hideFromIndex(),
            Text::make(__('app.tenchu'), 'tenchu')->hideFromIndex(),
            Text::make(__('app.diachi'), 'diachi')->hideFromIndex(),
            Number::make(__('app.dt'), 'dt'),

            Text::make(__('app.mdsd_2003'), 'mdsd_2003')->hideFromIndex(),
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
