<?php

namespace App\Providers;

use App\Models\User;
use App\Nova\Layouts\KdThuocBVTVStats;
use App\Nova\Metrics\Caytrongs;
use App\Nova\Metrics\NewThuadat;
use App\Nova\Metrics\NewUsers;
use App\Nova\Layouts\NonghoStats;
use App\Nova\Layouts\KdThuocBVTVStats;
use App\Nova\Layouts\KdNongsanStats;
use App\Support\Helper;
use IDF\HtmlCard\HtmlCard;
use Illuminate\Support\Facades\Gate;
use Larabase\Nova\Map\NovaMap;
use Larabase\NovaPage\NovaPage;
use Laravel\Nova\Fields\Text;
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

        NovaPage::setConfig([
            'icon' => '<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /> </svg>',
            'navTitle' => 'Statistics'
        ]);

        NovaPage::addLayout(NonghoStats::class);
        NovaPage::addLayout(KdThuocBVTVStats::class);
        NovaPage::addLayout(KdNongsanStats::class);

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
                    'type' => 'wms', // tile, wms, geojson, marker, ....
                    'title' => 'Nông hộ',
                    'active' => true,
                    'options' => ['url' => '/geogis/caytrong/wms', 'layers' => 'caytrong:v_nongho', 'zIndex' => 50],
                    'popup' => [
                        'url' => '/api/map/popup/nongho',
                        'options' => ['minWidth' => 350],
                        'actions' => [
                            ['type' => 'modal', 'title' => 'Chi tiết', 'url' => '/api/map/popup/nongho/modal'],
                            ['type' => 'link', 'title' => 'Liên kết', 'url' => '/nova/resources/nonghos/{id}'],
                        ]
                    ]
                ],
                [
                    'control' => 'overlay',
                    'type' => 'wms',
                    'title' => __('app.kd_thuoc_bvtv'),
                    'options' => ['url' => '/geogis/caytrong/wms', 'layers' => 'caytrong:kd_thuoc_bvtv', 'zIndex' => 60],
                    'popup' => [
                        'url' => '/api/map/popup/kd-thuoc-bvtv',
                        'options' => ['minWidth' => 320],
                        'actions' => [
                            ['type' => 'link', 'title' => 'Liên kết', 'url' => '/nova/resources/kd-thuoc-b-v-t-vs/{id}'],
                        ]
                    ]
                ],
                [
                    'control' => 'overlay',
                    'type' => 'wms',
                    'title' => __('app.kd_nongsan'),
                    'options' => ['url' => '/geogis/caytrong/wms', 'layers' => 'caytrong:kd_nongsan', 'zIndex' => 70],
                    'popup' => [
                        'url' => '/api/map/popup/kd-nongsan',
                        'options' => ['minWidth' => 320],
                        'actions' => [
                            ['type' => 'link', 'title' => 'Liên kết', 'url' => '/nova/resources/kd-nongsans/{id}'],
                        ]
                    ]
                ],
                [
                    'control' => 'overlay',
                    'type' => 'wms',
                    'title' => 'Ranh thửa đất',
                    'active' => true,
                    'options' => ['url' => '/geogis/caytrong/wms', 'layers' => 'caytrong:v_ranhthua', 'zIndex' => 40],

                ],
                [
                    'control' => 'overlay',
                    'type' => 'wms',
                    'title' => 'Thửa đất',
                    'options' => ['url' => '/geogis/caytrong/wms', 'layers' => 'caytrong:v_ranhthua', 'zIndex' => 30, 'styles' => 'v_thuadat'],
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
            (new NovaPage),
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
