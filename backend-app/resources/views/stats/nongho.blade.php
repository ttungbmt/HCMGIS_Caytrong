<?php
use \App\Support\Helper;
$format = fn($n) =>$n == 0 ? '' : Helper::numberFormat($n, 0);
$e_dt = fn($code, $ctr_id) => data_get($data->where('maquan', (string)$code)->where('loai_ctr_id', $ctr_id)->first(), 'dt_gt', '');
$e_ns = fn($code, $ctr_id) => data_get($data->where('maquan', (string)$code)->where('loai_ctr_id', $ctr_id)->first(), 'nangsuat_bq', '');
?>

<table class="table w-full mt-5">
    <thead>
    <tr>
        <th colspan="2">Tên chỉ tiêu</th>
        <th>Đơn vị tính</th>
        <th>Toàn tỉnh</th>
        @foreach($hcs as $hc)
            <th>{{$hc}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
        @foreach($ctrs as $ctr_id => $ctr)
        <tr>
            <td rowspan="5">{{$ctr}}</td>
            <td>Diện tích hiện có</td>
            <td>Ha</td>
            <td></td>
            @foreach($hcs as $code => $hc)
                <td></td>
            @endforeach
        </tr>
        <tr>
            <td>Trong đó: Trồng mới</td>
            <td>Ha</td>
            <td></td>
            @foreach($hcs as $code => $hc)
                <td></td>
            @endforeach
        </tr>
        <tr>
            <td>Diện tích cho SP</td>
            <td>Ha</td>
            <td>{{$dt = $format($data->where('loai_ctr_id', $ctr_id)->sum('dt_gt'))}}</td>
            @foreach($hcs as $code => $hc)
                <td>{{$format($e_dt($code, $ctr_id))}}</td>
            @endforeach
        </tr>
        <tr>
            <td>Năng suất trên DT cho SP</td>
            <td>Tạ/ha</td>
            <td>{{$ns = $format($data->where('loai_ctr_id', $ctr_id)->sum('nangsuat_bq'))}}</td>
            @foreach($hcs as $code => $hc)
                <td>{{$format($e_ns($code, $ctr_id))}}</td>
            @endforeach
        </tr>
        <tr>
            <td>Sản lượng thu hoạch</td>
            <td>Tấn</td>
            <td>{{$format(floatval($dt)*floatval($ns)/10)}}</td>
            @foreach($hcs as $code => $hc)
                <td>{{$format(floatval($e_dt($code, $ctr_id))*floatval($e_ns($code, $ctr_id))/10)}}</td>
            @endforeach
        </tr>
        @endforeach

    </tbody>
</table>
