<?php
namespace App\Models;


use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class GeoModel extends Model
{
    use PostgisTrait, Postgis;

    protected $postgisFields = [
        'geom',
    ];

    protected $postgisTypes = [
        'geom' => [
            'type' => 'Point',
            'geomtype' => 'geometry',
            'srid' => 4326
        ],
    ];
}
