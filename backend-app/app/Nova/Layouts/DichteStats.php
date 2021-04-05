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

        $q2 = Ranhthua::select('id')->whereIntersection($geom);

        $hc_case = $maquan ? ['table' => 'hc_phuong', 'code' => 'maphuong', 'label' => 'tenphuong'] : ['table' => 'hc_quan', 'code' => 'maquan', 'label' => 'tenquan'];
        $has_maquan =  $maquan ? "maquan = '{$maquan}'" : '1=1';
        $hc_geom =  (fn($col) => $geom ? ($col.' in ('.$q2->toSql().')') : '1=1');

        $q0 = DB::table(DB::raw('dichte dt'))
            ->leftJoin(DB::raw('nongho nh'), 'nh.id',  '=', 'dt.nongho_id')
            ->leftJoin(DB::raw('dm_loai_gh gh'), 'gh.id',  '=', 'dt.loai_gh_id')
            ->leftJoin(DB::raw('dm_caytrong ctr'), 'ctr.id',  '=', 'dt.loai_ctr_id')
            ->selectRaw(collect([
                $hc_case['code'],
                'COUNT ( DISTINCT loai_gh_id ) loai_gh',
                'COUNT ( DISTINCT thuoc_bvtv ) thuoc_bvtv',
                'COUNT ( DISTINCT loai_ctr_id ) loai_ctr',
                'ARRAY_TO_STRING(ARRAY_AGG (DISTINCT gh.ten),\', \') AS loai_ghs',
                'ARRAY_TO_STRING(ARRAY_AGG (DISTINCT thuoc_bvtv),\', \') AS thuoc_bvtvs',
                'ARRAY_TO_STRING(ARRAY_AGG (DISTINCT ctr.ten),\', \') AS loai_ctrs',
                'SUM(solan_vu) solan_vu',
                'AVG(hieuqua_sdt) hieuqua_sdt',
            ])->implode(', '))
            ->groupBy($hc_case['code'])
            ->whereRaw($has_maquan)
            ->whereRaw($loai_ctr_ids->isNotEmpty() ? "dt.loai_ctr_id in (".$loai_ctr_ids->implode(',').")" : '1=1')
            ->whereRaw($nhom_gh_ids->isNotEmpty() ? "gh.nhom_gh_id in (".$nhom_gh_ids->implode(',').")" : '1=1')
            ->whereRaw($hc_geom('nongho_id'))
        ;

        $data = DB::table(DB::raw($hc_case['table'].' hc'))
            ->selectRaw(collect([
                "hc.{$hc_case['code']} code",
                "hc.{$hc_case['label']} as label",
                'dt.*',
            ])->implode(', '))
            ->leftJoin(DB::raw("({$q0->toSql()}) dt"), 'dt.'.$hc_case['code'], '=', 'hc.'.$hc_case['code'])
            ->whereRaw($has_maquan)
            ->get()
        ;

        return [
            'html' => view('stats.dichte', compact('data'))->render()
        ];
    }
}
