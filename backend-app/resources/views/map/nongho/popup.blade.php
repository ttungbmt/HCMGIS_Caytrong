<div class="pop-content">
    <div class="pop-title">
        #{{$model->id}} {{$model->hoten}}
    </div>

    <div class="pop-body">
        <table class="table-sm w-full">
            <tbody>
            <tr>
                <td class="font-semibold">Quận huyện</td>
                <td>{{$model->quan->tenquan}}</td>
            </tr>
            <tr>
                <td class="font-semibold">Phường xã</td>
                <td>{{$model->phuong->tenphuong}}</td>
            </tr>
            <tr>
                <td class="font-semibold">Địa chỉ</td>
                <td>{{$model->diachi}}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>


