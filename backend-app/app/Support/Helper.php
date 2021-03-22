<?php
namespace App\Support;

use App\Models\HcPhuong;
use App\Models\HcQuan;
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
}
