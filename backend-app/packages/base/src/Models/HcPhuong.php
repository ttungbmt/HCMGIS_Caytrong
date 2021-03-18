<?php
namespace Larabase\Models;

use Larabase\Database\Eloquent\GeoModel;

class HcPhuong extends GeoModel
{
    protected $table = 'hc_phuong';

    protected $fillable = ['maphuong', 'maquan', 'tenphuong', 'tenquan', 'cap_hc'];

    protected $postgisTypes = [
        'geom' => [
            'type' => 'MultiPolygon',
            'geomtype' => 'geometry',
            'srid' => 4326
        ],
    ];

    public function quan()
    {
        return $this->belongsTo(HcQuan::class, 'maquan', 'maquan');
    }
}
