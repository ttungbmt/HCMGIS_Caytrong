<?php
namespace Larabase\Models;

use Illuminate\Support\Facades\Cache;
use Larabase\Database\Eloquent\GeoModel;

class HcQuan extends GeoModel
{
    protected $table = 'hc_quan';

    protected $fillable = ['tenquan', 'maquan', 'cap_hc'];

    protected $postgisTypes = [
        'geom' => [
            'type' => 'MultiPolygon',
            'geomtype' => 'geometry',
            'srid' => 4326
        ],
    ];

    public static function getCacheAll(){
        return Cache::rememberForever('dir.hc_quan', function () {
            return self::select(['id', 'maquan', 'tenquan'])->get();
        });
    }

    public function phuongs()
    {
        return $this->hasMany(HcPhuong::class, 'maquan', 'maquan');
    }
}

