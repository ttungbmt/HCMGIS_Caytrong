<table class="table w-full mt-5">
    <thead>
    <tr>
        <th>STT</th>
        <th class="text-left">Hành chính</th>
        <th>Loài gây hại</th>
        <th>Thuốc BVTV</th>
        <th>Cây trồng</th>
        <th>Số lần/ vụ</th>
        <th>Hiệu quả SDT (%)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $k => $v)
        <tr>
            <td class="text-center">{{$k+1}}</td>
            <td>{{$v->label}}</td>
            <td class="text-center">{{$v->loai_gh}}</td>
            <td class="text-center">{{$v->thuoc_bvtv}}</td>
            <td class="text-center">{{$v->loai_ctr}}</td>
            <td class="text-center">{{$v->solan_vu}}</td>
            <td class="text-center">{{$v->hieuqua_sdt ? number_format($v->hieuqua_sdt, 2) : $v->hieuqua_sdt}}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="2"></td>
        <td class="text-center font-semibold">{{$data->sum('loai_gh')}}</td>
        <td class="text-center font-semibold">{{$data->sum('thuoc_bvtv')}}</td>
        <td class="text-center font-semibold">{{$data->sum('loai_ctr')}}</td>
        <td class="text-center font-semibold">{{$data->sum('solan_vu')}}</td>
        <td class="text-center font-semibold">{{number_format($data->avg('hieuqua_sdt'), 2)}}</td>
    </tr>
    </tbody>
</table>
