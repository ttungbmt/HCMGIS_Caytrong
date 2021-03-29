<?php

namespace App\Nova\Layouts;

use App\Models\Caytrong;
use App\Nova\LoaiGh;
use App\Models\NhomGh;
use App\Models\HcQuan;
use App\Models\Dichte;
use App\Models\Ranhthua;
use Illuminate\Support\Facades\DB;
use Larabase\Nova\Map\Fields\Map;
use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Fields\Select;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use OptimistDigital\MultiselectField\Multiselect;

class DichteStats extends Layout
{
    public static $path = 'stats/dichte';

    public static function title()
    {
	return 'app.dichte';
    }

    public function fields(Request $request)
    {
        $quan = HcQuan::pluck('tenquan', 'maquan');
        $ctr = Caytrong::orderBy('id')->pluck('ten', 'id');
        $ngh = NhomGh::orderby('id')->pluck('ten', 'id');
//dd($ngh);
        return [
            Select::make(__('app.quan'), 'maquan')->options($quan)->displayUsingLabels()->nullable(),
            Multiselect::make(__('app.loai_ctr'), 'loai_ctr_ids')->options($ctr->all()),
            Multiselect::make(__('app.nhom_gh'), 'nhom_gh_ids')->options($ngh->all()),
            Map::make(__('Map'), 'geom')->setStatsEditor()
        ];
    }

    public function asController(NovaRequest $request, $fields, $resource)
    {
        $loai_ctr_ids = collect(json_decode($resource->loai_ctr_ids))->map(fn($v) => intval($v));
        $nhom_gh_ids = collect(json_decode($resource->nhom_gh_ids))->map(fn($n) => intval($n));
        $maquan = $resource->maquan;
        $geom = ($geojson = data_get($resource, 'polygon_geom.data')) ? json_encode($geojson) : null;

        $ctrs = Caytrong::whereIn('id', $loai_ctr_ids)->get()->map(fn($v, $k) => ['id' => $v->id, 'ten' => $v->ten, 'alias' => 'dt_'.($k+1)]);
        $nghs = NhomGh::whereIn('id', $nhom_gh_ids)->get()->map(fn($n, $l) => ['id' => $n->id, 'ten' => $n->ten, 'alias' => 'gh_'.($l+1)]);
//dd($nghs);

        $q2 = Ranhthua::select('id')->whereIntersection($geom);

        $hc_case = $maquan ? ['table' => 'hc_phuong', 'code' => 'maphuong', 'label' => 'tenphuong'] : ['table' => 'hc_quan', 'code' => 'maquan', 'label' => 'tenquan'];
        $hc_geom =  (fn($col) => $geom ? ($col.' in ('.$q2->toSql().')') : '1=1');
        $maquan = null;

        $q0 = DB::table('nongho')->selectRaw($hc_case['code'].', count(*)')
            ->groupBy($hc_case['code'])
            ->andFilterWhere(['maquan' => $maquan])
            ->whereRaw($hc_geom('id'))
        ;
        
        $q1 = DB::table('dichte')
            ->selectRaw('dt.'.$hc_case['code'])
            ->leftJoin(DB::raw('dichte dt'), 'dt.id',  '=', 'dt.nghs')
            ->groupBy('nh.'.$hc_case['code'])
            ->andFilterWhere(['maquan' => $maquan])
            ->whereRaw($has_qtsx('nongho_id'))
            ->whereRaw($hc_geom('nongho_id'));

//dd($q1->ToSql());

        $data = DB::table(DB::raw($hc_case['table'].' hc'))
            ->selectRaw("hc.{$hc_case['code']} code, hc.{$hc_case['label']} as label, nh.count")
            ->leftJoin(DB::raw("({$q0->toSql()}) nh"), 'nh.'.$hc_case['code'], '=', 'hc.'.$hc_case['code'])
            ->whereRaw($maquan ? "maquan = '{$maquan}'" : '1=1');
           // ->get();
dd($data->ToSql());

        return [
            'html' => view('stats.dichte', compact('data', 'ctrs'))->render()
        ];
    }
}
