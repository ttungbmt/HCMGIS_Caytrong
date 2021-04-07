<?php


namespace App\Nova\Metrics;


use Coroowicaksono\ChartJsIntegration\BarChart;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DientichSxChart extends BarChart
{
    public function __construct($component = null)
    {
        parent::__construct($component);


        $data = Cache::remember('stats.dientich_sx', 3 * 60 * 60, function () {
            return DB::query()->fromRaw('dientich_sx dt')
                ->selectRaw("ctr.ten, SUM(dt_gt) dt_gt, SUM(dt_vg) dt_vg")
                ->leftJoin(DB::raw('dm_caytrong ctr'), 'ctr.id', '=', 'dt.loai_ctr_id')
                ->groupBy('ctr.ten')
                ->get();
        });

        $format_number = fn($v) => $v ? number_format($v, 2) : $v;

        $this->title('Diện tích sản xuất (ha)')
            ->animations([
                'enabled' => true,
                'easing' => 'easeinout',
            ])
            ->series([
                [
                    'label' => 'Truyền thống',
                    'backgroundColor' => '#16AFE5',
                    'data' => $data->pluck('dt_gt')->map($format_number)
                ],
                [
                    'label' => 'VietGAP',
                    'backgroundColor' => '#EEBB4C',
                    'data' => $data->pluck('dt_vg')->map($format_number)
                ]
            ])
            ->options([
                'xaxis' => [
                    'categories' => $data->pluck('ten')
                ],
            ]);

    }
}
