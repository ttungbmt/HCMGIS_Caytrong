<?php
namespace Larabase\Database\Eloquent;

use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

class GeoModel extends Model
{
    use PostgisTrait;

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
