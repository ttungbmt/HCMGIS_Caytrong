import L, {GeoJSON} from 'leaflet'
import flip from '@turf/flip'
import { getCoord } from '@turf/invariant'
import pointOnSurface from '@turf/point-on-feature';

export const geomToLatLng = (geom) => geom ? L.latLng(getCoord(flip(geom))) : null

export const coordsToLatLng = (geometry) => {
    return GeoJSON.coordsToLatLng(getCoord(pointOnSurface(geometry)))
}
