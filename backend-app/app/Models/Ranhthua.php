<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Larabase\Database\Eloquent\GeoModel;

class Ranhthua extends GeoModel
{
    use HasFactory;

    protected $table = 'pg_ranhthua';

    protected $fillable = [];

    public $timestamps = false;

    protected $postgisTypes = [
        'geom' => [
            'type' => 'MultiPolygon',
            'geomtype' => 'geometry',
            'srid' => 4326
        ],
    ];

    public function phuong()
    {
        return $this->belongsTo(HcPhuong::class, 'maphuong', 'maphuong');
    }
}
