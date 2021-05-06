<?php

namespace App\Nova\Layouts;

use App\Models\Caytrong;
use App\Models\HcQuan;
use App\Models\Nongho;
use App\Models\Ranhthua;
use App\Nova\Filters\QuytrinhSxFilter;
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
        return 'app.nongho';
    }

    public function fields(Request $request)
    {
        $quan = HcQuan::pluck('tenquan', 'maquan');
        $ctr = Caytrong::orderBy('id')->pluck('ten', 'id');
        $qtsx = array_flip(with(new QuytrinhSxFilter)->options($request));

        return [
//            Select::make(__('app.quan'), 'maquan')->options($quan)->displayUsingLabels()->nullable(),
//            Multiselect::make(__('app.loai_ctr'), 'loai_ctr_ids')->options($ctr->all()),
//            BooleanGroup::make(__('app.quytrinh_sx'), 'quytrinh_sx')->options($qtsx),
            Map::make(__('Map'), 'geom')->setStatsEditor()
        ];
    }

    public function asController(NovaRequest $request, $fields, $resource)
    {
//        $loai_ctr_ids = collect(json_decode($resource->loai_ctr_ids))->map(fn($v) => intval($v));
//        $maquan = $resource->maquan;
//        $qtsx = collect($resource->quytrinh_sx)->filter()->keys();
        $geom = ($geojson = data_get($resource, 'polygon_geom.data')) ? json_encode($geojson) : null;


        $data = DB::table('dientich_sx as dt')
            ->selectRaw(collect([
                'maquan', 'loai_ctr_id', 'SUM(dt_gt+dt_vg) dt_gt', 'SUM(nangsuat_bq) nangsuat_bq'
            ])->implode(', '))
            ->leftJoin('nongho as nh', 'nh.id', '=', 'dt.nongho_id')
            ->groupByRaw('maquan, loai_ctr_id')->get()
//            ->map(function ($i){
//                $i['dt_gt'] = $i['dt_gt'];
//                $i['nangsuat_bq'] = $i['nangsuat_bq'];
//                return $i;
//            })
        ;

        $ctrs = Caytrong::orderBy('id')->pluck('ten', 'id');
        $hcs = HcQuan::orderBy('tenquan')->pluck('tenquan', 'maquan');

//        $ctrs = Caytrong::whereIn('id', $loai_ctr_ids)->get()->map(fn($v, $k) => ['id' => $v->id, 'ten' => $v->ten, 'alias' => 'dt_'.($k+1)]);
//
//        $q_dt =  DB::table('dientich_sx')
//            ->selectRaw('nongho_id')->groupBy('nongho_id')
//            ->when($qtsx->contains('th'), fn($q) => $q->whereRaw('dt_gt > 0'))
//            ->when($qtsx->contains('vg'), fn($q) => $q->whereRaw('dt_vg > 0'))
//        ;
//
//        $q2 = Ranhthua::select('id')->whereIntersection('geom', $geom);
//
//        $hc_case = $maquan ? ['table' => 'hc_phuong', 'code' => 'maphuong', 'label' => 'tenphuong'] : ['table' => 'hc_quan', 'code' => 'maquan', 'label' => 'tenquan'];
//        $has_qtsx = (fn($col) => [$qtsx->isNotEmpty(), fn($q) => $q->whereRaw("{$col} in ({$q_dt->toSql()})")]);
//        $hc_geom =  (fn($col) => [$geom, fn($q) => $q->whereRaw("{$col} in ('{$q2->toSql()}')")]);
//
//        $q0 = DB::table('nongho')->selectRaw($hc_case['code'].', count(*)')
//            ->groupBy($hc_case['code'])
//            ->whereFilter('maquan', $maquan)
//            ->when(...$has_qtsx('id'))
//            ->when(...$hc_geom('id'))
//        ;
//
//        $q1 = DB::table(DB::raw('dientich_sx dt'))
//            ->selectRaw('nh.'.$hc_case['code'])
//            ->leftJoin(DB::raw('nongho nh'), 'nh.id',  '=', 'dt.nongho_id')
//            ->groupBy('nh.'.$hc_case['code'])
//            ->whereFilter('maquan', $maquan)
//            ->when(...$has_qtsx('nongho_id'))
//            ->when(...$hc_geom('nongho_id'));
//
//        if($ctrs->isEmpty()) $q1 = $q1->addSelect(DB::raw('SUM (dt_gt) dt, SUM(nangsuat_bq) nangsuat_bq'));
//        else $q1 = $q1->addSelect(DB::raw($ctrs->map(fn($v, $k) => "SUM(dt_gt) FILTER (WHERE dt.loai_ctr_id = {$v['id']}) {$v['alias']}")->values()->implode(', ')));
//
//        $data = DB::table(DB::raw($hc_case['table'].' hc'))
//            ->selectRaw("hc.{$hc_case['code']} code, hc.{$hc_case['label']} as label, nh.count, ".($ctrs->isEmpty() ? 'dt' : $ctrs->pluck('alias')->implode(', ')))
//            ->leftJoinSub($q0, 'nh', 'nh.'.$hc_case['code'], '=', 'hc.'.$hc_case['code'])
//            ->leftJoinSub($q1, 'dt', 'dt.'.$hc_case['code'], '=', 'hc.'.$hc_case['code'])
//            ->whereFilter('maquan', $maquan)
//            ->get()
//            ->map(function ($v) use ($ctrs){
//                $aliases = $ctrs->pluck('alias');
//                $cols = $aliases->merge($ctrs->isEmpty() ? ['dt'] : [])->merge([])->each(function ($col) use($v){
//                    if(is_numeric($v->{$col})) $v->{$col} = number_format($v->{$col}, 2);
//                });
//
//                $v->dt_total = $ctrs->isEmpty() ? $v->dt : $aliases->map(fn($a) => $v->$a)->sum();
//                return $v;
//            })
//        ;

//        dd($hcs, $ctrs, $data);

        return [
//            'html' => view('stats.nongho', compact('data', 'ctrs'))->render()
            'html' => view('stats.nongho', compact('hcs', 'ctrs', 'data'))->render()
        ];
    }
}
