<?php

namespace App\Nova\Layouts;

use App\Models\Caytrong;
use App\Models\HcQuan;
use App\Models\Nongho;
use App\Models\Ranhthua;
use Illuminate\Support\Facades\DB;
use Larabase\Nova\Map\Fields\Map;
use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Fields\Select;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use OptimistDigital\MultiselectField\Multiselect;

class NonghoStats extends Layout
{
    public static $path = 'stats/nongho';

    public static function title()
    {
        return __('app.nongho');
    }

    public function fields(Request $request)
    {
        $quan = HcQuan::pluck('tenquan', 'maquan');
        $ctr = Caytrong::orderBy('id')->pluck('ten', 'id');

        return [
            Select::make(__('app.quan'), 'maquan')->options($quan)->displayUsingLabels()->nullable(),
            Multiselect::make(__('app.loai_ctr'), 'loai_ctr_ids')->options($ctr->all()),
            BooleanGroup::make(__('app.quytrinh_sx'), 'quytrinh_sx')->options([
                'th' => 'Truyền thống',
                'vg' => 'VietGAP',
            ]),
            Map::make(__('Map'), 'geom')->setStatsEditor()
        ];
    }

    public function asController(NovaRequest $request, $fields, $resource)
    {
        $loai_ctr_ids = collect(json_decode($resource->loai_ctr_ids))->map(fn($v) => intval($v));
        $maquan = $resource->maquan;
        $qtsx = collect($resource->quytrinh_sx)->filter()->keys();
        $geom = ($geojson = data_get($resource, 'polygon_geom.data')) ? json_encode($geojson) : null;

        $ctrs = Caytrong::whereIn('id', $loai_ctr_ids)->get()->map(fn($v, $k) => ['id' => $v->id, 'ten' => $v->ten, 'alias' => 'dt_'.($k+1)]);

        $q =  DB::table('dientich_sx')->selectRaw('nongho_id')->groupBy('nongho_id');
        $qtsx->each(function ($k) use(&$q){
            $k === 'vg' && $q->whereRaw('dt_gt > 0');
            $k === 'tr' && $q->whereRaw('dt_vg > 0');
        });

        $q2 = Ranhthua::select('id')->whereIntersection($geom);

        $hc_case = $maquan ? ['table' => 'hc_phuong', 'code' => 'maphuong', 'label' => 'tenphuong'] : ['table' => 'hc_quan', 'code' => 'maquan', 'label' => 'tenquan'];
        $has_qtsx =  (fn($col) => $qtsx->isNotEmpty() ? ($col.' in ('.$q->toSql().')') : '1=1');
        $hc_geom =  (fn($col) => $geom ? ($col.' in ('.$q2->toSql().')') : '1=1');
        $maquan = null;

        $q0 = DB::table('nongho')->selectRaw($hc_case['code'].', count(*)')
            ->groupBy($hc_case['code'])
            ->andFilterWhere(['maquan' => $maquan])
            ->whereRaw($has_qtsx('id'))
            ->whereRaw($hc_geom('id'))
        ;

        $q1 = DB::table(DB::raw('dientich_sx dt'))
            ->selectRaw('nh.'.$hc_case['code'])
            ->leftJoin(DB::raw('nongho nh'), 'nh.id',  '=', 'dt.nongho_id')
            ->groupBy('nh.'.$hc_case['code'])
            ->andFilterWhere(['maquan' => $maquan])
            ->whereRaw($has_qtsx('nongho_id'))
            ->whereRaw($hc_geom('nongho_id'));

        if($ctrs->isEmpty()) $q1 = $q1->addSelect(DB::raw('SUM (dt_gt) dt'));
        else $q1 = $q1->addSelect(DB::raw($ctrs->map(fn($v, $k) => "SUM(dt_gt) FILTER (WHERE dt.loai_ctr_id = {$v['id']}) {$v['alias']}")->values()->implode(', ')));

        $data = DB::table(DB::raw($hc_case['table'].' hc'))
            ->selectRaw("hc.{$hc_case['code']} code, hc.{$hc_case['label']} as label, nh.count, ".($ctrs->isEmpty() ? 'dt' : $ctrs->pluck('alias')->implode(', ')))
            ->leftJoin(DB::raw("({$q0->toSql()}) nh"), 'nh.'.$hc_case['code'], '=', 'hc.'.$hc_case['code'])
            ->leftJoin(DB::raw("({$q1->toSql()}) dt"), 'dt.'.$hc_case['code'], '=', 'hc.'.$hc_case['code'])
            ->whereRaw($maquan ? "maquan = '{$maquan}'" : '1=1')
            ->get()
            ->map(function ($v) use ($ctrs){
                $aliases = $ctrs->pluck('alias');
                $cols = $aliases->merge($ctrs->isEmpty() ? ['dt'] : [])->merge([])->each(function ($col) use($v){
                    if(is_numeric($v->{$col})) $v->{$col} = number_format($v->{$col}, 2);
                });

                $v->dt_total = $ctrs->isEmpty() ? $v->dt : $aliases->map(fn($a) => $v->$a)->sum();
                return $v;
            })
        ;

        return [
            'html' => view('stats.nongho', compact('data', 'ctrs'))->render()
        ];
    }
}
