<?php
namespace App\Support;

use App\Models\HcPhuong;
use App\Models\HcQuan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Orlyapps\NovaBelongsToDepend\NovaBelongsToDepend;

class Helper
{
    public static function hcFields()
    {
        $hc_quan = HcQuan::getCacheAll()->sortBy('tenquan', SORT_NATURAL)->values();

        return [
            NovaBelongsToDepend::make(__('app.quan'), 'quan', \App\Nova\HcQuan::class)
                ->placeholder(__('app.prompt_qh'))
                ->rules('required')
                ->fillUsing(function ($request, $model, $attribute, $requestAttribute) {
                    $model->{$attribute} = data_get(HcQuan::find($request->input($requestAttribute)), 'maquan');
                })
                ->options($hc_quan),

            NovaBelongsToDepend::make(__('app.phuong'), 'phuong', \App\Nova\HcPhuong::class)
                ->placeholder(__('app.prompt_px'))
                ->optionsResolve(function ($quan) {
                    return $quan->phuongs()->get(['id', 'maphuong', 'tenphuong'])->sortBy('tenphuong', SORT_NATURAL)->values();
                })
                ->rules('required')
                ->dependsOn('quan')
                ->fillUsing(function ($request, $model, $attribute, $requestAttribute) {
                    $model->{$attribute} = data_get(HcPhuong::find($request->input($requestAttribute)), 'maphuong');
                }),
        ];
    }

    public static function getTpExtent(){
        return Cache::remember('tp_extent', 31*24*3600, function () {
            $data = DB::table('hc_quan')->selectRaw('ST_AsGeoJSON(Box2D(ST_Extent(geom))::geometry) as geometry')->first();
            $geometry = data_get($data, 'geometry');
            return json_decode($geometry, true) ;
        });
    }

    public static function getTpBoundary(){
        return Cache::remember('tp_boundary', 31*24*3600, function () {
            $data = DB::table('hc_quan')->selectRaw('ST_AsGeoJSON(ST_Simplify(ST_Buffer(ST_Union(geom)::geography, 100)::geometry, 0.0001)) as geometry')->first();
            $geometry = data_get($data, 'geometry');
            return json_decode($geometry, true) ;
        });
    }

    public static function numberFormat($number, $decimals = 2){
        return $number ? number_format($number, $decimals) : $number;
    }
}
