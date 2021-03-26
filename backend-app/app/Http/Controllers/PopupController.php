<?php
namespace App\Http\Controllers;

use App\Models\KdNongsan;
use App\Models\KdThuocBVTV;
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


        return $this->render('map.nongho.popup', compact('model'));
    }

    public function thuadat(Request $request){
        $id = $request->input('properties.id');

        $model = Ranhthua::find($id);

        $data = [
            ['label' => __('app.sh_bando'), 'value' => 'sh_bando'],
            ['label' => __('app.sh_thua'), 'value' => 'sh_thua'],
            ['label' => __('app.mdsd_2003'), 'value' => 'mdsd_2003'],
            ['label' => __('app.ma_ld'), 'value' => 'ma_ld'],
            ['label' => __('app.tenchu'), 'value' => 'tenchu'],
            ['label' => __('app.diachi'), 'value' => 'diachi'],
            ['label' => __('app.diadanh'), 'value' => 'diadanh'],
            ['label' => __('app.dt'), 'value' => 'dt'],
            ['label' => __('app.dt_phaply'), 'value' => 'dt_phaply'],
            ['label' => __('app.dt_thocu'), 'value' => 'dt_thocu'],
            ['label' => __('app.dtsd'), 'value' => 'dtsd'],
            ['label' => __('app.kh_2003'), 'value' => 'kh_2003'],
            ['label' => __('app.kihieu_ld'), 'value' => 'kihieu_ld'],
            ['label' => __('app.maphuong'), 'value' => 'maphuong'],
            ['label' => __('app.mathua'), 'value' => 'mathua'],

        ];

        return $this->render('map.thuadat.popup', compact('model', 'fields'));
    }

    public function kd_nongsan(Request $request){
        $id = $request->input('properties.id');
        $model = KdNongsan::find($id);

        $fields = [
            ['label' => __('app.ten'), 'value' => 'ten'],
            ['label' => __('app.quan'), 'value' => 'quan.tenquan'],
            ['label' => __('app.phuong'), 'value' => 'phuong.tenphuong'],
            ['label' => __('app.diachi'), 'value' => 'diachi'],
        ];

        return $this->render('map.kd_nongsan.popup', ['model' => $model, 'fields' => $fields]);
    }

    public function kd_thuoc_bvtv(Request $request){
        $id = $request->input('properties.id');
        $model = KdThuocBVTV::find($id);

        $fields = [
            ['label' => __('app.ten'), 'value' => 'ten'],
            ['label' => __('app.quan'), 'value' => 'quan.tenquan'],
            ['label' => __('app.phuong'), 'value' => 'phuong.tenphuong'],
            ['label' => __('app.diachi'), 'value' => 'diachi'],
        ];

        return $this->render('map.kd_nongsan.popup', ['model' => $model, 'fields' => $fields]);
    }

    protected function render($view, $data){
        return [
            'data' => $data['model'],
            'html' => view($view, $data)->render(),
        ];
    }

}
