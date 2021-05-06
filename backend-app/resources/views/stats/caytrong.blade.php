<?php
use \App\Support\Helper;
$format = fn($n, $decimal = 0) => $n == 0 ? '' : Helper::numberFormat($n, $decimal);
$e_v = fn($code, $ctr_id, $type = 'dt_hc') => data_get($data->where('maquan', (string)$code)->where('loai_ctr_id', (string)$ctr_id)->first(), $type, '');
$e_ns = fn($code, $ctr_id) => data_get($data->where('maquan', (string)$code)->where('loai_ctr_id', $ctr_id)->first(), 'nangsuat_bq', '');

?>

<table class="table w-full mt-5">
    <thead>
    <tr>
        <th class="" colspan="2">Tên chỉ tiêu</th>
        <th class="text-left">Đơn vị tính</th>
        <th class="text-left">Toàn tỉnh</th>
        @foreach($hcs as $hc)
            <th class="text-left">{{$hc}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($ctrs as $ctr_id => $ctr)
        @php
            $sl = $format($data->where('loai_ctr_id', $ctr_id)->sum('sanluong_th'))
        @endphp

        <tr>
            <td rowspan="5">{{$ctr}}</td>
            <td>Diện tích hiện có</td>
            <td>Ha</td>
            <td>{{$dt = $format($data->where('loai_ctr_id', $ctr_id)->sum('dt_hc'))}}</td>
            @foreach($hcs as $code => $hc)
                <td>{{$format($e_v($code, $ctr_id, 'dt_hc'))}}</td>
            @endforeach
        </tr>
        <tr>
            <td>Trong đó: Trồng mới</td>
            <td>Ha</td>
            <td>{{$dt = $format($data->where('loai_ctr_id', $ctr_id)->sum('dt_trm'))}}</td>
            @foreach($hcs as $code => $hc)
                <td>{{$format($e_v($code, $ctr_id, 'dt_trm'))}}</td>
            @endforeach
        </tr>
        <tr>
            <td>Diện tích cho SP</td>
            <td>Ha</td>
            <td>{{$dt = $format($data->where('loai_ctr_id', $ctr_id)->sum('dt_sp'))}}</td>
            @foreach($hcs as $code => $hc)
                <td>{{$format($e_v($code, $ctr_id, 'dt_sp'))}}</td>
            @endforeach
        </tr>
        <tr>
            <td>Năng suất trên DT cho SP</td>
            <td>Tạ/ha</td>
            <td>{{$ns = $format($sl/$dt*10, 2)}}</td>
            @foreach($hcs as $code => $hc)
                <td>{{$format($e_v($code, $ctr_id, 'sanluong_th')/$e_v($code, $ctr_id, 'dt_sp')*10, 2)}}</td>
            @endforeach
        </tr>
        <tr>
            <td>Sản lượng thu hoạch</td>
            <td>Tấn</td>
            <td>{{$sl}}</td>
            @foreach($hcs as $code => $hc)
                <td>{{$format($e_v($code, $ctr_id, 'sanluong_th'))}}</td>
            @endforeach
        </tr>
    @endforeach

    </tbody>
</table>
