<table class="table w-full mt-5">
    <thead>
    <tr>
        <th>STT</th>
        <th class="text-left">Hành chính</th>
        <th>Số cửa hàng nông sản</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $k => $v)
        <tr>
            <td class="text-center">{{$k+1}}</td>
            <td>{{$v->label}}</td>
            <td class="text-center">{{$v->count}}</td>
        </tr>
    @endforeach
    <tr>
        <td></td>
        <td class="text-center font-semibold">{{$data->sum('count')}}</td>
        
    </tr>
    </tbody>
</table>