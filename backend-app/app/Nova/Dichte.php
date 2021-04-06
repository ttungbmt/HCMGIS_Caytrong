<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Filters\CaytrongFilter;
use Larabase\Nova\Cards\FiltersSummary;
use Larabase\Nova\Actions\DownloadExcel;

class Dichte extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Dichte::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'thuoc_bvtv';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'thuoc_bvtv',
    ];

    public static function group()
    {
        return __('app.caytrong');
    }


    public static function label()
    {
        return __('app.dichte');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $caytrongs = \App\Models\Caytrong::pluck('ten', 'id');

        return [
            ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make(__('app.nongho'), 'nongho', 'App\Nova\Nongho')->searchable(),
            Text::make(__('app.thuoc_bvtv'), 'thuoc_bvtv'),
            BelongsTo::make(__('app.caytrong'), 'caytrong', 'App\Nova\Caytrong')->showCreateRelationButton(),
            Text::make(__('app.solan_vu'), 'solan_vu'),
            Text::make(__('app.hieuqua_sdt'), 'hieuqua_sdt'),
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
            CaytrongFilter::make(),
            //DichteFilter::make(),
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
        return [
            new DownloadExcel,
        ];
    }
}
