<?php

namespace Larabase\Nova\Map\Http\Controllers;

use Larabase\Nova\Map\NovaMap;

class MapController
{
    public function __invoke()
    {
        $config = NovaMap::getConfig();

        $baseConfig = [
            'layers' => [],
            'controls' => [
                'layers' => [
                    'position' => 'topright',
                    'autoZIndex' => false
                ],
                'fullscreen' => [
                    'position' => 'bottomright',
                ],
                'measure' => [
                    'position' => 'bottomright',
                    'measureControlClasses' => ['fal', 'fa-ruler-combined', 'text-base'],
                    'measureControlLabel' => '',
                    'options' => [
                        'measureControlTitleOn' => 'Bật thước đo',
                        'measureControlTitleOff' => 'Tắt thước đo'
                    ]
                ],
                'locate' => [
                    'position' => 'bottomright',
                    'icon' => 'fal fa-location',
                ],
                'legend' => [
                    'position' => 'bottomleft',
                    'title' => 'Chú giải',
                    'items' => [
                        ['type' => 'rectangle', 'fillColor' => '#88C047', 'stroke' => 'white', 'label' => 'Vùng trồng'],
                        ['type' => 'rectangle', 'fillColor' => '#F0245E', 'stroke' => '#6bb70e', 'label' => 'Vùng trồng VietGAP'],
                    ]
                ]
            ]
        ];

        return response()->json(array_merge($baseConfig, $config));
    }
}
