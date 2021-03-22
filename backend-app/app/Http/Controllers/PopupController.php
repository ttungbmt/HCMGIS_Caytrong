<?php
namespace App\Http\Controllers;

use App\Models\Ranhthua;
use Illuminate\Http\Request;

class PopupController extends Controller
{
    public function nongho(Request $request){
        $nongho_id = $request->input('properties.nongho_id');

        $model = \App\Models\Nongho::with([
            'quan' => fn($query) => $query->select(['tenquan', 'maquan']),
            'phuong' => fn($query) => $query->select(['tenphuong', 'maphuong']),
        ])->where('id', $nongho_id)->first();

        return [
            'data' => $model,
            'html' => view('map.nongho.popup', ['model' => $model])->render(),
        ];
    }

    public function nonghoModal(Request $request){
        $nongho_id = $request->input('id');

        $model = \App\Models\Nongho::with([
            'quan' => fn($query) => $query->select(['tenquan', 'maquan']),
            'phuong' => fn($query) => $query->select(['tenphuong', 'maphuong']),
            'thuadats', 'dientichs'
        ])->where('id', $nongho_id)->first();

        return [
            'data' => $model,
            'html' => view('map.nongho.modal', ['model' => $model])->render(),
        ];
    }

    public function thuadat(Request $request){
        $id = $request->input('properties.id');

        $model = Ranhthua::find($id);

        $data = [
            ['label' => 'Tên chủ', 'value' => 'tenchu'],
            ['label' => 'Địa chỉ', 'value' => 'diachi'],
            ['label' => 'Địa danh', 'value' => 'diadanh'],
            ['label' => 'Diện tích', 'value' => 'dt'],
            ['label' => 'Diện tích pháp lý', 'value' => 'dt_phaply'],
            ['label' => 'Diện tích thổ cư', 'value' => 'dt_thocu'],
            ['label' => 'Diện tích sử dụng', 'value' => 'dtsd'],
            ['label' => 'Kí hiệu 2003', 'value' => 'kh_2003'],
            ['label' => 'Kí hiệu loại đất', 'value' => 'kihieu_ld'],
            ['label' => 'Mã loại đất', 'value' => 'ma_ld'],
            ['label' => 'Mã phường', 'value' => 'maphuong'],
            ['label' => 'Mã thửa', 'value' => 'mathua'],
            ['label' => 'MDSD 2003', 'value' => 'mdsd_2003'],
            ['label' => 'Số hiệu bản đồ', 'value' => 'sh_bando'],
            ['label' => 'Số hiệu thửa', 'value' => 'sh_thua'],
        ];

        return [
            'data' => $model,
            'html' => view('map.thuadat.popup', ['model' => $model, 'fields' => $data])->render(),
        ];
    }
}
