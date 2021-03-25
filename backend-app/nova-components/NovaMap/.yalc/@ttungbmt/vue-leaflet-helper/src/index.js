import _ from 'lodash-es'
import {GeoJSON, geoJSON, ExtraMarkers} from 'leaflet'
import {getCoord} from '@turf/invariant'
import pointOnSurface from '@turf/point-on-feature'

export function toMapControls(data) {
    return _.transform(data, (result, value, key) => {
        if(value || value.enabled){
            let ctrl = _.omit(value, ['enabled'])

            ctrl.name = key
            switch (key) {
                default:
                    ctrl.component = `l-control-${key}`
                    break
            }

            result.push(ctrl)
        }
    }, [])
}

// type, title, active, url

export function toMapLayers(data) {
    return _.map(data, layer => {
        let {id, type, options = {}, title = 'None', active = false, data, events} = layer,
            props = {
                id,
                type,
                name: title,
                visible: active,
                options: _.omit(options, ['url'])
            }

        if(layer.is_model) props.is_model = layer.is_model
        if(layer.popup) props.popup = layer.popup

        if(layer.control) props.layerType = layer.control === 'basemap' ? 'base' : 'overlay'
        if(layer.key)  props.options.key = layer.key
        if(options.url)  props.url = urlTemplate(options.url, options)

        switch (type) {
            case 'wms':
                props = _.merge(props, {
                    ...options,
                    component: _.get(layer, 'component', 'l-wms-tile-layer'),
                    baseUrl: props.url,
                    transparent: true,
                    format: 'image/png',
                })

                break
            case 'tile':
                props.component = _.get(layer, 'component', 'l-tile-layer')
                break
            case 'marker':
                props.component = _.get(layer, 'component', 'l-marker')
                props.latLng = data
                // if(!props.icon) {
                //     props.icon = ExtraMarkers.icon({
                //         icon: 'far fa-circle extra-marker-icon',
                //         markerColor: 'cyan',
                //         prefix: 'fa'
                //     })
                // }

                break
            case 'geojson':
                props.component = _.get(layer, 'component', 'l-geojson')
                props.geojson = data
                break
            default:
                break
        }

        return props
    })
}

export const getUrlLayer = (latlng, layer) => {
    let map = layer._map,
        point = map.latLngToContainerPoint(latlng, map.getZoom()),
        size = map.getSize(),
        wmsParams = layer.wmsParams,
        params = {
            request: 'GetFeatureInfo',
            service: 'WMS',
            srs: 'EPSG:4326',
            styles: wmsParams.styles,
            transparent: wmsParams.transparent,
            version: wmsParams.version,
            format: wmsParams.format,
            bbox: map.getBounds().toBBoxString(),
            height: size.y,
            width: size.x,
            layers: layer.wmsParams.layers,
            query_layers: layer.wmsParams.layers,
            info_format: 'application/json'
        };

    params[params.version === '1.3.0' ? 'i' : 'x'] = Math.round(point.x);
    params[params.version === '1.3.0' ? 'j' : 'y'] = Math.round(point.y);

    if(wmsParams.cql_filter) params.cql_filter = wmsParams.cql_filter

    return layer._url + L.Util.getParamString(params, layer._url, true)
}

export function urlTemplate(string, data) {
    _.map(data, (v, k) => {
        if(string.search(`{${k}}`)) string = string.replace(`{${k}}`, v)
    })
    return string
}

export const getBounds = (data) => geoJSON(data).getBounds()

export const coordsToLatLng = (geometry) => GeoJSON.coordsToLatLng(getCoord(pointOnSurface(geometry)))
