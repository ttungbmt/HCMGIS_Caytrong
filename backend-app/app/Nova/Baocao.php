<?php

namespace App\Nova;

use App\Models\HcQuan;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Whitecube\NovaFlexibleContent\Flexible;

class Baocao extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Baocao::class;

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
        'id',
    ];

    public static function label()
    {
        return __('app.baocao');
    }


    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $hcs = HcQuan::getCacheAll()->sortBy('tenquan', SORT_NATURAL)->pluck('tenquan', 'maquan');
        $ctrs = \App\Models\Caytrong::pluck('ten', 'id');
        $step = 1e-15;

        return [
            ID::make(__('ID'), 'id')->sortable(),
            Text::make(__('Title'), 'title'),
            Flexible::make(__('Content'), 'data')
                ->addLayout('Quận huyện', 'stats_loai_ctr', [
                    Select::make(__('app.quan'), 'maquan')->options($hcs)->displayUsingLabels(),
                    Select::make(__('app.loai_ctr_id'), 'loai_ctr_id')->options($ctrs)->displayUsingLabels(),
                    Number::make(__('app.dt_hc'), 'dt_hc')->withMeta(['step' => $step]),
                    Number::make(__('app.dt_trm'), 'dt_trm')->withMeta(['step' => $step]),
                    Number::make(__('app.dt_sp'), 'dt_sp')->withMeta(['step' => $step]),
                    Number::make(__('app.sanluong_th'), 'sanluong_th')->withMeta(['step' => $step]),
                ])->button('Thêm mới')->onlyOnForms(),

            Text::make('', function () use($hcs, $ctrs){
                return view('stats.caytrong', [
                    'hcs' => $hcs->filter(fn($tenquan, $maquan) => in_array($maquan, $this->data->pluck('attributes.maquan')->all())),
                    'ctrs' => $ctrs->filter(fn($ten, $id) => in_array($id, $this->data->pluck('attributes.loai_ctr_id')->all())),
                    'data' => $this->data->pluck('attributes')
                ])->render();
            })->onlyOnDetail()->size('w-full')->asHtml()
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
