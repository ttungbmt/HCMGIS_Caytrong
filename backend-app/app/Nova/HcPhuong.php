<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Larabase\Nova\Map\Fields\Map;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

class HcPhuong extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\HcPhuong::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'tenphuong';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'maphuong', 'tenphuong',
    ];

    public static $globallySearchable = false;

    public static function group()
    {
        return __('Directory');
    }

    public static function label()
    {
        return __('app.hc_phuong');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $quan = optional(\App\Models\HcQuan::find($request->input('quan')));

        return [
            ID::make(__('ID'), 'id')->sortable(),
            Map::make(__('Map'), 'geom'),
            Text::make(__('app.maquan'), 'maquan')->sortable()->onlyOnIndex(),
            BelongsTo::make(__('app.tenquan'), 'quan', 'App\Nova\HcQuan')->fillUsing(function ($request, $model, $attribute, $requestAttribute) use($quan){
                $model->{$attribute} = $quan->maquan;
                $model->tenquan = $quan->tenquan;
            }),
            Text::make(__('app.maphuong'), 'maphuong')->sortable()->creationRules('unique:'.self::$model)->rules('required'),
            Text::make(__('app.tenphuong'), 'tenphuong')->sortable()->creationRules([Rule::unique(self::$model)->where(function ($query) use($quan){
                return $query->where('maquan', $quan->maquan);
            })]),

            // HasMany::make(__('app.thuadat'), 'thuadats', 'App\Nova\Ranhthua')
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
