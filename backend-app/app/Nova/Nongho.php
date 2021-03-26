<?php
namespace App\Nova;

use App\Models\LoaiGh;
use App\Models\NhomGh;
use App\Nova\Fields\Place;
use App\Support\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Larabase\Nova\Map\Fields\Map;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Panel;
use Whitecube\NovaFlexibleContent\Flexible;

class Nongho extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Nongho::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'hoten';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'hoten',
    ];

    public static function group()
    {
        return __('app.caytrong');
    }

    public static function label()
    {
        return __('app.nongho');
    }

    public function __construct($resource)
    {
        parent::__construct($resource);


    }


    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $dichtesField = Flexible::make(null)->fullWidth()->button(__('Add new'));
        $dientichsField = Flexible::make(null)->fullWidth()->button(__('Add new'));

        $loaiGhs = LoaiGh::all();
        $caytrongs = \App\Models\Caytrong::pluck('ten', 'id');

        $ctrs = $caytrongs->map(fn($v, $k) => $dientichsField->addLayout($v, $k, [
            Number::make(__('app.dt_gt'), 'dt_gt'),
            Number::make(__('app.dt_vg'), 'dt_vg'),
            Text::make(__('app.ma_cn'), 'ma_cn'),
            Number::make(__('app.sovu_ct'), 'sovu_ct'),
            Number::make(__('app.nangsuat_bq'), 'nangsuat_bq'),
        ]));


        $nhomGhs= NhomGh::pluck('ten', 'id')->map(fn($v, $k) => $dichtesField->addLayout($v, $k, [
            Select::make(__('app.loai_gh'), 'loai_gh_id')->options($loaiGhs->where('nhom_gh_id', $k)->pluck('ten', 'id')->all())->displayUsingLabels(),
            Text::make(__('app.thuoc_bvtv'), 'thuoc_bvtv'),
            Select::make(__('app.loai_ctr'), 'loai_ctr_id')->options($ctrs)->displayUsingLabels()->nullable(),
            Text::make(__('app.solan_vu'), 'solan_vu'),
            Text::make(__('app.hieuqua_sdt'), 'hieuqua_sdt'),
        ]));


        return [
            ID::make(__('ID'), 'id')->sortable(),
            Map::make(__('Map'), 'geom')->hideFromIndex(),

            new Panel('Nông hộ', [
                ...Helper::hcFields(),
                Text::make(__('app.hoten'), 'hoten')->sortable()->rules('required')->showOnIndex(),
                Place::make(__('app.diachi'), 'diachi'),
                Text::make(__('app.dienthoai'), 'dienthoai'),
                Trix::make(__('app.ghichu'), 'ghichu')
            ]),

            new Panel('Thửa đất', [
                BelongsToMany::make(__('app.thuadats'), 'thuadats', 'App\Nova\Ranhthua')->showOnCreating()
            ]),

            new Panel('Diện tích sản xuất', [
                $dientichsField
            ]),
            new Panel('Dịch tễ', [
                $dichtesField
            ]),

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
