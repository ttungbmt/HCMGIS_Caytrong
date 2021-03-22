<?php

//$nonghos = DB::table('nongho as nh')
//    ->select(DB::raw('nh.maquan, count(*)'))
//    ->groupBy('nh.maquan')
//    ->get();

$nonghos = DB::table('hc_quan as qh')
    ->select(DB::raw('qh.maquan, qh.tenquan, nh.count'))
    ->leftJoin(DB::raw('(SELECT maquan, count(*) FROM nongho GROUP BY maquan) nh'), 'nh.maquan', '=', 'qh.maquan')
    ->get();
?>

<loading-view :loading="true">
    <table class="table w-full">
        <thead>
        <tr>
            <th class="bg-white">#</th>
            <th class="bg-white">Quận huyện</th>
            <th class="bg-white">Số hộ</th>
            <th class="bg-white">Diện tích gieo trồng</th>
        </tr>
        </thead>
        <tbody>
        @foreach($nonghos as $k => $i)
            <tr>
                <td>{{$k+1}}</td>
                <td>{{$i->tenquan}}</td>
                <td>{{$i->count}}</td>
                <td></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</loading-view>



