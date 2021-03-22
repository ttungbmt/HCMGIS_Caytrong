<?php

namespace App\Providers;

use App\Models\User;
use App\Nova\Metrics\Caytrongs;
use App\Nova\Metrics\NewThuadat;
use App\Nova\Metrics\NewUsers;
use App\Support\Helper;
use IDF\HtmlCard\HtmlCard;
use Illuminate\Support\Facades\Gate;
use Larabase\Nova\Map\NovaMap;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        NovaMap::setConfig(function (){
            $layers = [
                [
                    'control' => 'basemap',
                    'type' => 'tile',
                    'title' => 'Google',
                    'options' => ['url' => 'http://mt2.google.com/vt/lyrs=m&x={x}&y={y}&z={z}'],
                    'boundary' => true
                ],
                [
                    'control' => 'basemap',
                    'type' => 'tile',
                    'title' => 'Map4D',
                    'options' => ['url' => 'http://rtile.map4d.vn/all/2d/{z}/{x}/{y}.png'],
                    'active' => true,
                    'boundary' => true
                ],
                [
                    'control' => 'basemap',
                    'type' => 'tile',
                    'title' => 'Ảnh vệ tinh',
                    'options' => ['url' => 'http://mt2.google.com/vt/lyrs=s&x={x}&y={y}&z={z}'],
                    'boundary' => true
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
                    'active' => true,
                    'options' => ['url' => '/geogis/caytrong/wms', 'layers' => 'caytrong:hc_phuong', 'zIndex' => 100]
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
                            ['type' => 'link', 'title' => 'Liên kết', 'url' => '/nova/resources/nonghos/{id}'],
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
                        'options' => ['minWidth' => 320],
                        'actions' => [
                            ['type' => 'link', 'title' => 'Liên kết', 'url' => '/nova/resources/ranhthuas/{id}'],
                        ]
                    ]
                ],
            ];

            return [
                'config' => [
                    'center' => [10.240095, 106.373147],
                    'zoom' => 11
                ],
                'layers' => $layers,
                'extent' => Helper::getTpExtent(),
                'boundary' => Helper::getTpBoundary()
            ];
        });
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            (new Caytrongs),
            (new NewThuadat),
            (new NewUsers),
            (new HtmlCard())->width('1/3')->view('cards.stats_hc')->width('2/3'),
//            (new \Larabase\Nova\Map\NovaMapCard)
//                ->width('full')
//                ->height('full')
//                ->configUrl('/api/map/config')
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        $tools = [
            (new NovaMap()),
            (new \Mastani\NovaPasswordReset\NovaPasswordReset)->canSeeWhen('users.change-password', User::class),
            (new \Vyuldashev\NovaPermission\NovaPermissionTool)
                ->roleResource(\Larabase\Nova\Resources\Role::class)
                ->permissionResource(\Larabase\Nova\Resources\Permission::class),
            (new \Infinety\Filemanager\FilemanagerTool)->canSeeWhen('filemanager', User::class),
        ];

        return $tools;
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
