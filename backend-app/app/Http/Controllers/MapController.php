<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MapController extends Controller
{
    public function config(){
        $layers = [
            [
                'control' => 'basemap',
                'type' => 'tile',
                'title' => 'Google',
                'options' => ['url' => 'http://mt2.google.com/vt/lyrs=m&x={x}&y={y}&z={z}']
            ],
            [
                'control' => 'basemap',
                'type' => 'tile',
                'title' => 'Map4D',
                'options' => ['url' => 'http://rtile.map4d.vn/all/2d/{z}/{x}/{y}.png'],
                'active' => true,
            ],
            [
                'control' => 'basemap',
                'type' => 'tile',
                'title' => 'Ảnh vệ tinh',
                'options' => ['url' => 'http://mt2.google.com/vt/lyrs=s&x={x}&y={y}&z={z}']
            ],
            [
                'control' => 'overlay',
                'type' => 'wms',
                'title' => 'Ranh quận huyện',
                'options' => ['url' => '/geogis/caytrong/wms', 'layers' => 'caytrong:hc_quan']
            ],
            [
                'control' => 'overlay',
                'type' => 'wms',
                'title' => 'Ranh phường xã',
                'options' => ['url' => '/geogis/caytrong/wms', 'layers' => 'caytrong:hc_phuong']
            ],
            [
                'control' => 'overlay',
                'type' => 'wms',
                'title' => 'Nông hộ',
                'active' => true,
                'options' => ['url' => '/geogis/caytrong/wms', 'layers' => 'caytrong:v_nongho', 'zIndex' => 50],
                'popup' => [
                    'url' => '/api/map/popup/nongho',
                    'options' => ['minWidth' => 200],
                    'actions' => [
                        ['type' => 'modal', 'title' => 'Chi tiết', 'url' => '/api/map/popup/nongho/modal'],
                        ['type' => 'link', 'title' => 'Liên kết'],
                    ]
                ]
            ],
            [
                'control' => 'overlay',
                'type' => 'wms',
                'title' => 'Ranh thửa đất',
                'active' => true,
                'options' => ['url' => '/geogis/caytrong/wms', 'layers' => 'caytrong:v_ranhthua', 'zIndex' => 40],
                'popup' => [
                    'url' => '/api/map/popup/thuadat',
                    'options' => ['minWidth' => 320]
                ]
            ],
        ];

        return [
            'layers' => $layers
        ];
    }

}
