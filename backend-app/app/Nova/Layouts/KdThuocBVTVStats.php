<?php
namespace App\Nova\Layouts;

use App\Models\HcQuan;
use App\Models\KdThuocBVTV;
use App\Models\Ranhthua;
use Illuminate\Support\Facades\DB;
use Larabase\Nova\Map\Fields\Map;
use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Fields\Select;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use OptimistDigital\MultiselectField\Multiselect;

class KdThuocBVTVStats
{
   public static $path = 'stats/KdThuocBVTV';

    public static function title()
    {
        return 'app.kd_thuoc_bvtv';
    }

    public function fields(Request $request)
    {
        $quan = HcQuan::pluck('tenquan', 'maquan');
        $kd = KdThuocBVTV::orderBy('id')->pluck('ten', 'id');

        return [
            Select::make(__('app.quan'), 'maquan')->options($quan)->displayUsingLabels()->nullable(),
            Map::make(__('Map'), 'geom')->setStatsEditor()
        ];
    }

    public function asController(NovaRequest $request, $fields, $resource)
    {
        $maquan = $resource->maquan;
        $geom = ($geojson = data_get($resource, 'polygon_geom.data')) ? json_encode($geojson) : null;

        $hc_case = $maquan ? ['table' => 'hc_phuong', 'code' => 'maphuong', 'label' => 'tenphuong'] : ['table' => 'hc_quan', 'code' => 'maquan', 'label' => 'tenquan'];
        $hc_geom =  (fn($col) => [$geom, fn($q) => $q->whereIn($col, fn($q) => $q->select('id')->from('pg_ranhthua')->whereIntersection('geom', $geom))]);

        $q0 = KdThuocBVTV::selectRaw($hc_case['code'].', count(*)')
            ->groupBy($hc_case['code'])
            ->whereFilter('maquan', $maquan)
            ->when(...$hc_geom('id'))
        ;

        $data = DB::query()->fromRaw($hc_case['table'].' hc')
            ->selectRaw("hc.{$hc_case['code']} code, hc.{$hc_case['label']} as label, nh.count")
            ->leftJoinSub($q0, 'nh', 'nh.'.$hc_case['code'], '=', 'hc.'.$hc_case['code'])
            ->whereFilter('maquan', $maquan)
            ->get()
        ;

        return [
            'html' => view('stats.kdthuocbvtv', compact('data'))->render()
        ];
    }
}
