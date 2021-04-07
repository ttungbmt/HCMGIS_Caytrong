<?php
use Illuminate\Database\Query\Builder;
use App\Support\Helper;

$value = Cache::remember('stats.nongho', 60*60, function () {
    return DB::table('hc_quan as qh')
        ->select(DB::raw('qh.maquan, qh.tenquan, nh.count, dt.dt_sx'))
        ->leftJoinSub(fn(Builder $q) => $q->from('nongho')->selectRaw('maquan, count(*)')->groupBy('maquan'), 'nh', 'nh.maquan', '=', 'qh.maquan')
        ->leftJoinSub(function (Builder $q) {
            $q
                ->selectRaw('maquan, SUM(dt_gt)+SUM(dt_vg) dt_sx')->from('dientich_sx as dt')
                ->leftJoin('nongho as nh', 'nh.id', '=', 'dt.nongho_id')
                ->groupBy('maquan');
        }, 'dt', 'dt.maquan', '=', 'qh.maquan')
        ->get();
});
?>

<table class="table w-full">
    <thead>
    <tr>
        <th class="bg-white">#</th>
        <th class="bg-white">Quận huyện</th>
        <th class="bg-white">Số hộ</th>
        <th class="bg-white">Diện tích (ha)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($value as $k => $i)
        <tr>
            <td>{{$k+1}}</td>
            <td>{{$i->tenquan}}</td>
            <td>{{$i->count}}</td>
            <td>{{Helper::numberFormat($i->dt_sx)}}</td>
        </tr>
    @endforeach
    </tbody>
</table>



