var _ = require('lodash-es');
var leaflet = require('leaflet');
var invariant = require('@turf/invariant');
var pointOnSurface = require('@turf/point-on-feature');

function _interopDefaultLegacy (e) { return e && typeof e === 'object' && 'default' in e ? e : { 'default': e }; }

var ___default = /*#__PURE__*/_interopDefaultLegacy(_);
var pointOnSurface__default = /*#__PURE__*/_interopDefaultLegacy(pointOnSurface);

function _extends() {
  _extends = Object.assign || function (target) {
    for (var i = 1; i < arguments.length; i++) {
      var source = arguments[i];

      for (var key in source) {
        if (Object.prototype.hasOwnProperty.call(source, key)) {
          target[key] = source[key];
        }
      }
    }

    return target;
  };

  return _extends.apply(this, arguments);
}

function toMapControls(data) {
  return ___default['default'].transform(data, function (result, value, key) {
    if (value || value.enabled) {
      var ctrl = ___default['default'].omit(value, ['enabled']);

      switch (key) {
        default:
          ctrl.component = "l-control-" + key;
          break;
      }

      result.push(ctrl);
    }
  }, []);
} // type, title, active, url

function toMapLayers(data) {
  return ___default['default'].map(data, function (layer) {
    var id = layer.id,
        type = layer.type,
        _layer$options = layer.options,
        options = _layer$options === void 0 ? {} : _layer$options,
        _layer$title = layer.title,
        title = _layer$title === void 0 ? 'None' : _layer$title,
        _layer$active = layer.active,
        active = _layer$active === void 0 ? false : _layer$active,
        data = layer.data,
        props = {
      id: id,
      type: type,
      name: title,
      visible: active,
      options: ___default['default'].omit(options, ['url'])
    };
    if (layer.is_model) props.is_model = layer.is_model;
    if (layer.popup) props.popup = layer.popup;
    if (layer.control) props.layerType = layer.control === 'basemap' ? 'base' : 'overlay';
    if (layer.key) props.options.key = layer.key;
    if (options.url) props.url = urlTemplate(options.url, options);

    switch (type) {
      case 'wms':
        props = ___default['default'].merge(props, _extends({}, options, {
          component: ___default['default'].get(layer, 'component', 'l-wms-tile-layer'),
          baseUrl: props.url,
          transparent: true,
          format: 'image/png'
        }));
        break;

      case 'tile':
        props.component = ___default['default'].get(layer, 'component', 'l-tile-layer');
        break;

      case 'marker':
        props.component = ___default['default'].get(layer, 'component', 'l-marker');
        props.latLng = data;
        break;
    }

    return props;
  });
}
var getUrlLayer = function getUrlLayer(latlng, layer) {
  var map = layer._map,
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
  if (wmsParams.cql_filter) params.cql_filter = wmsParams.cql_filter;
  return layer._url + L.Util.getParamString(params, layer._url, true);
};
function urlTemplate(string, data) {
  ___default['default'].map(data, function (v, k) {
    if (string.search("{" + k + "}")) string = string.replace("{" + k + "}", v);
  });

  return string;
}
var getBounds = function getBounds(data) {
  return leaflet.geoJSON(data).getBounds();
};
var coordsToLatLng = function coordsToLatLng(geometry) {
  return leaflet.GeoJSON.coordsToLatLng(invariant.getCoord(pointOnSurface__default['default'](geometry)));
};

exports.coordsToLatLng = coordsToLatLng;
exports.getBounds = getBounds;
exports.getUrlLayer = getUrlLayer;
exports.toMapControls = toMapControls;
exports.toMapLayers = toMapLayers;
exports.urlTemplate = urlTemplate;
