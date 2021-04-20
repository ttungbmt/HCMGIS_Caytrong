<table class="table w-full mt-5">
    <thead>
    <tr>
        <th rowspan="2">STT</th>
        <th rowspan="2" class="text-left">Hành chính</th>
        <th rowspan="2">Số hộ</th>
        <th colspan="{{$ctrs->count()+1}}">{{__('app.dt_gt')}}</th>
    </tr>
    <tr>
        <th>Tổng</th>
        @if($ctrs->isNotEmpty())
            @foreach($ctrs as $v)
                <th>{{$v['ten']}}</th>
            @endforeach
        @endif
    </tr>
    </thead>
    <tbody>
    @foreach($data as $k => $v)
        <tr>
            <td class="text-center">{{$k+1}}</td>
            <td>{{$v->label}}</td>
            <td class="text-center">{{$v->count}}</td>
            <td class="text-center">{{$v->dt_total}}</td>
            @if($ctrs->isNotEmpty())
                @foreach($ctrs->pluck('alias') as $a)
                    <td class="text-center">{{$v->$a}}</td>
                @endforeach
            @endif
        </tr>
    @endforeach
    <tr>
        <td colspan="2"></td>
        <td class="text-center font-semibold">{{$data->sum('count')}}</td>
        <td class="text-center font-semibold">{{$data->sum('dt_total')}}</td>
        @if($ctrs->isNotEmpty())
            @foreach($ctrs->pluck('alias') as $a)
                <td class="text-center">{{$data->sum($a)}}</td>
            @endforeach
        @endif
    </tr>
    </tbody>
</table>
