<?php
$dts = $model->dientichs;
$ctrs = $dts->map(fn($i) => $i->caytrong->ten)->implode(', ');
?>

<div class="pop-content">
    <div class="pop-title">
        #{{$model->id}} {{$model->hoten}} ({{$ctrs}}):
    </div>

    <div class="pop-body">
        <table class="table-sm w-full">
            <tbody>
            <tr>
                <td class="font-semibold">Huyện - Xã</td>
                <td>{{$model->quan->tenquan}} - {{$model->phuong->tenphuong}}</td>
            </tr>
            <tr>
                <td class="font-semibold">Địa chỉ</td>
                <td>{{$model->diachi}}</td>
            </tr>
            <tr>
                <td class="font-semibold">Cây trồng</td>
                <td>{{$ctrs}}</td>
            </tr>
            <tr>
                <td class="font-semibold">DT (ha)</td>
                <td>{{$dts->sum('dt_gt')}}</td>
            </tr>
            <tr>
                <td class="font-semibold">DT VietGAP (ha)</td>
                <td>{{$dts->sum('dt_vg')}}</td>
            </tr>
            <tr>
                <td class="font-semibold">Năng suất BQ</td>
                <td>{{$dts->sum('nangsuat_bq')}}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>


