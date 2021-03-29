<?php
namespace App\Models;

trait Postgis
{
    public function scopeWhereIntersection($query, $geom){
        return $query->whereRaw("ST_Intersects(geom, ST_GeomFromGeoJSON('{$geom}'))");
    }
}
