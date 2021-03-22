<?php

namespace App\Models;

class Ranhthua extends Model
{
    protected $table = 'pg_ranhthua';

    protected $fillable = ['maphuong', 'sh_bando', 'sh_thua',];

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
