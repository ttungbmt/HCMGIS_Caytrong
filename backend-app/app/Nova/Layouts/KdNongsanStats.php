<?php
namespace App\Nova\Layouts;

use App\Models\HcQuan;
use App\Models\KdNongsan;
use App\Models\Ranhthua;
use Illuminate\Support\Facades\DB;
use Larabase\Nova\Map\Fields\Map;
use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Fields\Select;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use OptimistDigital\MultiselectField\Multiselect;

class KdNongsanStats
{
   public static $path = 'stats/KdNongsan';

    public static function title()
    {
        return ('app.kd_nongsan');
    }

    public function fields(Request $request)
    {
        $quan = HcQuan::pluck('tenquan', 'maquan');
        $kd = KdNongsan::orderBy('id')->pluck('ten', 'id');
        return [
            Select::make(__('app.quan'), 'maquan')->options($quan)->displayUsingLabels()->nullable(),
            Map::make(__('Map'), 'geom')->setStatsEditor()
        ];
    }

    public function asController(NovaRequest $request, $fields, $resource)
    {
        $maquan = $resource->maquan;
        $geom = ($geojson = data_get($resource, 'polygon_geom.data')) ? json_encode($geojson) : null;

        $q2 = Ranhthua::select('id')->whereIntersection('geom', $geom);

        $has_maquan = $maquan ? "maquan = '{$maquan}'" : '1=1';
        $hc_case = $maquan ? ['table' => 'hc_phuong', 'code' => 'maphuong', 'label' => 'tenphuong'] : ['table' => 'hc_quan', 'code' => 'maquan', 'label' => 'tenquan'];
        $hc_geom =  (fn($col) => $geom ? ($col.' in ('.$q2->toSql().')') : '1=1');

        $q0 = KdNongsan::selectRaw($hc_case['code'].', count(*)')
            ->groupBy($hc_case['code'])
            ->whereRaw($has_maquan)
            ->whereRaw($hc_geom('id'))
        ;

        $data = DB::table(DB::raw($hc_case['table'].' hc'))
            ->selectRaw("hc.{$hc_case['code']} code, hc.{$hc_case['label']} as label, nh.count")
            ->leftJoinSub($q0, 'nh', 'nh.'.$hc_case['code'], '=', 'hc.'.$hc_case['code'])
            ->whereRaw($maquan ? "maquan = '{$maquan}'" : '1=1')
            ->get()
        ;
        return [
            'html' => view('stats.kdnongsan', compact('data'))->render()
        ];
    }
}
