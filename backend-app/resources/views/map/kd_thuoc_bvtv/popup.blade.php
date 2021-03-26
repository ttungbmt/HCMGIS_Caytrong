<div class="pop-content">
    <div class="pop-title">
        #{{$model->ten}}
    </div>

    <div class="pop-body">
        <table class="table-sm">
            <tbody>
            @foreach($fields as $field)
                <tr>
                    <td class="font-semibold" style="width: 135px;">{{$field['label']}}</td>
                    <td>{{data_get($model, $field['value'], '')}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>


