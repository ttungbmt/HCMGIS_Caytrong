<div class="flex pb-2">
    <div class="px-1 font-semibold">
        {{$model->diachi}}
    </div>
    <div class="px-1 font-semibold">
        {{$model->quan->tenquan}}
    </div>
</div>

@php
    $thudatsCount = $model->thuadats->count();
@endphp

<table class="table-sm w-full">
    <tbody>
    <tr>
        <td rowspan="{{4 + $thudatsCount}}">Thông tin nông hộ</td>
        <td>Họ tên</td>
        <td colspan="6">{{$model->hoten}}</td>
    </tr>
    <tr>
        <td>Địa chỉ</td>
        <td colspan="6">{{$model->diachi}}</td>
    </tr>
    <tr>
        <td>Số điện thoại</td>
        <td colspan="6">{{$model->dienthoai}}</td>
    </tr>
    <tr>
        <td rowspan="{{1 + $thudatsCount}}">Thông tin thửa</td>
        <td>Số tờ</td>
        <td>Số thửa</td>
        <td>Diện tích</td>
        <td>Đường</td>
        <td>Tổ</td>
        <td>Ấp</td>
    </tr>
    @foreach($model->thuadats as $rt)
        <tr>
            <td>{{$rt->sh_bando}}</td>
            <td>{{$rt->sh_thua}}</td>
            <td>{{$rt->pivot->dt}}</td>
            <td>{{$rt->pivot->diachi}}</td>
            <td>{{$rt->to_dp}}</td>
            <td>{{$rt->khupho}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@if($model->dientichs->count())
    <table class="table-sm w-full mt-4">
        <thead>
        <tr>
            <th colspan="2"></th>
            <th>DTGT (ha)</th>
            <th>DT VietGAP</th>
            <th>Mã chứng nhận</th>
            <th>Số vụ canh tác/năm</th>
            <th colspan="3">Năng suất bình quân (tấn/ha/vụ)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($model->dientichs as $dt)
            <tr>
                @if ($loop->first)
                    <td rowspan="3">Diện tích sản xuất</td>
                @endif
                <td>{{data_get($dt, 'caytrong.ten')}}</td>
                <td>{{$dt->dt_gt}}</td>
                <td>{{$dt->dt_vg}}</td>
                <td>{{$dt->ma_cn}}</td>
                <td>{{$dt->sovu_ct}}</td>
                <td></td>
                <td></td>
                <td>{{$dt->nangsuat_bq}}</td>
            </tr>
        @endforeach
        <tr>
            <td>Tổng cộng</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        </tbody>
    </table>
@else
    <p class="pt-2">Không có thông tin diện tích sản xuất</p>
@endif

@if($model->dichtes->count())
    <table class="table-sm w-full mt-4">
        <thead>
        <tr>
            <th colspan="2"></th>
            <th>Tên loài gây hại</th>
            <th>Thuốc BVTV sử dụng</th>
            <th>Nhóm cây trồng</th>
            <th>Số lần/vụ</th>
            <th>Hiệu quả sử dụng thuốc (%)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($model->dichtes as $dt)
            <tr>
                @if ($loop->first)
                    <td rowspan="6">Thông tin dịch tễ</td>
                @endif
                @php
                    $nhom_gh_id = data_get($dt, 'loai_gh.nhom_gh.id');
                    $count = $model->dichtes->filter(function ($i) use($nhom_gh_id){
                        return data_get($i, 'loai_gh.nhom_gh.id') === $nhom_gh_id;
                    })->count();
                @endphp

                @if ($count > 1 && $loop->first)
                    <td rowspan="{{$count}}">{{data_get($dt, 'loai_gh.nhom_gh.ten')}}</td>
                @else
                    @if($count <= 1) <td>{{data_get($dt, 'loai_gh.nhom_gh.ten')}}</td> @endif
                @endif

                <td>{{data_get($dt, 'loai_gh.ten')}}</td>
                <td>{{$dt->thuoc_bvtv}}</td>
                <td>{{data_get($dt, 'loai_ctr.ten')}}</td>
                <td>{{$dt->solan_vu}}</td>
                <td>{{$dt->hieuqua_sdt}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <p class="pt-2">Không có thông tin dịch tễ</p>
@endif


