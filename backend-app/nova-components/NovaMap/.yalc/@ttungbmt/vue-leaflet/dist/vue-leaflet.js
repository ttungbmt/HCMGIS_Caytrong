'use strict';

Object.defineProperty(exports, '__esModule', { value: true });

var vue2Leaflet = require('vue2-leaflet');
var LDrawToolbar = require('vue2-leaflet-draw-toolbar');
require('leaflet-boundary-canvas');
var leaflet = require('leaflet');
var __vue_normalize__ = require('vue-runtime-helpers/dist/normalize-component.mjs');
require('leaflet.browser.print/dist/leaflet.browser.print');
require('leaflet.fullscreen');
require('leaflet.fullscreen/Control.FullScreen.css');
var screenfull = require('screenfull');
var lodashEs = require('lodash-es');
var vueLeafletHelper = require('@ttungbmt/vue-leaflet-helper');

function _interopDefaultLegacy (e) { return e && typeof e === 'object' && 'default' in e ? e : { 'default': e }; }

var LDrawToolbar__default = /*#__PURE__*/_interopDefaultLegacy(LDrawToolbar);
var __vue_normalize____default = /*#__PURE__*/_interopDefaultLegacy(__vue_normalize__);
var screenfull__default = /*#__PURE__*/_interopDefaultLegacy(screenfull);

//
/*
* Legend: https://bando.net.vn/wp-content/uploads/2020/02/Khi-Hau-VN.jpg
* https://github.com/ptma/Leaflet.Legend
* https://github.com/consbio/Leaflet.HtmlLegend
* */

var script$7 = {
  name: 'LControlLegend',
  mixins: [vue2Leaflet.ControlMixin, vue2Leaflet.OptionsMixin],
  props: {
    items: Array,
    title: String
  },
  computed: {},
  mounted: function mounted() {
    var _this = this;

    var $legend = this.$refs.legend.innerHTML;
    var options = vue2Leaflet.optionsMerger(Object.assign({}, this.controlOptions, {
      title: this.title
    }), this);
    this.mapObject = leaflet.control(options);
    vue2Leaflet.propsBinder(this, this.mapObject, this.$options.props);

    this.mapObject.onAdd = function (map) {
      var div = leaflet.DomUtil.create('div', 'info legend');
      div.innerHTML = $legend;
      return div;
    };

    this.mapObject.addTo(this.$parent.mapObject);
    this.$nextTick(function () {
      /**
       * Triggers when the component is ready
       * @type {object}
       * @property {object} mapObject - reference to leaflet map object
       */
      _this.$emit('ready', _this.mapObject);
    });
  },
  methods: {
    getStyle: function getStyle(i) {
      var style = "background-color: ".concat(i.fillColor, ";");
      if (i.color) style += "border: 1px solid ".concat(i.color, ";");
      return style;
    }
  }
};

/* script */
var __vue_script__$7 = script$7;
/* template */

var __vue_render__$3 = function __vue_render__() {
  var _vm = this;

  var _h = _vm.$createElement;

  var _c = _vm._self._c || _h;

  return _c('div', {
    ref: "legend",
    staticStyle: {
      "display": "none"
    }
  }, [_vm._t("default"), _vm._v(" "), _c('div', {
    staticClass: "leaflet-bar leaflet-legend leaflet-legend-expanded"
  }, [_vm.title ? _c('div', {
    staticClass: "leaflet-legend-title"
  }, [_vm._v(_vm._s(_vm.title))]) : _vm._e(), _vm._v(" "), _c('div', {
    staticClass: "legend-items"
  }, _vm._l(_vm.items, function (i) {
    return _c('div', {
      staticClass: "legend-row"
    }, [_c('div', {
      staticClass: "leaflet-legend-html"
    }, [_c('div', {
      "class": "lengend-" + i.type,
      style: _vm.getStyle(i)
    })]), _vm._v(" "), _c('div', {
      staticClass: "lengend-label"
    }, [_vm._v(_vm._s(i.label))])]);
  }), 0)])], 2);
};

var __vue_staticRenderFns__$3 = [];
/* style */

var __vue_inject_styles__$7 = undefined;
/* scoped */

var __vue_scope_id__$7 = undefined;
/* module identifier */

var __vue_module_identifier__$7 = undefined;
/* functional template */

var __vue_is_functional_template__$7 = false;
/* style inject */

/* style inject SSR */

/* style inject shadow dom */

var __vue_component__$7 = /*#__PURE__*/__vue_normalize____default['default']({
  render: __vue_render__$3,
  staticRenderFns: __vue_staticRenderFns__$3
}, __vue_inject_styles__$7, __vue_script__$7, __vue_scope_id__$7, __vue_is_functional_template__$7, __vue_module_identifier__$7, false, undefined, undefined, undefined);

/**
 * Add any custom component as a leaflet control-scale
 */

var script$6 = {
  name: 'LControlPrint',
  mixins: [vue2Leaflet.ControlMixin, vue2Leaflet.OptionsMixin],
  props: {},
  mounted: function mounted() {
    var _this = this;

    var options = vue2Leaflet.optionsMerger(Object.assign({}, this.controlOptions), this);
    this.mapObject = leaflet.control.browserPrint(options);
    vue2Leaflet.propsBinder(this, this.mapObject, this.$options.props);
    this.mapObject.addTo(this.$parent.mapObject);
    leaflet.DomEvent.on(this.$parent.mapObject, this.$listeners);
    this.$nextTick(function () {
      /**
       * Triggers when the component is ready
       * @type {object}
       * @property {object} mapObject - reference to leaflet map object
       */
      _this.$emit('ready', _this.mapObject);
    });
  },
  render: function render() {
    return null;
  }
};

/* script */
var __vue_script__$6 = script$6;
/* template */

/* style */

var __vue_inject_styles__$6 = undefined;
/* scoped */

var __vue_scope_id__$6 = undefined;
/* module identifier */

var __vue_module_identifier__$6 = undefined;
/* functional template */

var __vue_is_functional_template__$6 = undefined;
/* style inject */

/* style inject SSR */

/* style inject shadow dom */

var __vue_component__$6 = /*#__PURE__*/__vue_normalize____default['default']({}, __vue_inject_styles__$6, __vue_script__$6, __vue_scope_id__$6, __vue_is_functional_template__$6, __vue_module_identifier__$6, false, undefined, undefined, undefined);

if (!window['screenfull']) window['screenfull'] = screenfull__default['default'];
/**
 * Add any custom component as a leaflet control-fullscrren
 */

var script$5 = {
  name: 'LControlFullscreen',
  mixins: [vue2Leaflet.ControlMixin, vue2Leaflet.OptionsMixin],
  props: {
    title: {
      type: String,
      "default": 'Full Screen'
    },
    titleCancel: {
      type: String,
      "default": 'Exit Full Screen'
    },
    content: String,
    forceSeparateButton: {
      type: Boolean,
      "default": true
    },
    forcePseudoFullscreen: {
      type: Boolean,
      "default": false
    },
    fullscreenElement: {
      type: Boolean,
      "default": false
    }
  },
  mounted: function mounted() {
    var _this = this;

    var options = vue2Leaflet.optionsMerger(Object.assign({}, this.controlOptions, {
      content: this.content
    }), this);
    console.log(options);
    this.mapObject = leaflet.control.fullscreen(options);
    vue2Leaflet.propsBinder(this, this.mapObject, this.$options.props);
    this.mapObject.addTo(this.$parent.mapObject);
    this.$nextTick(function () {
      /**
       * Triggers when the component is ready
       * @type {object}
       * @property {object} mapObject - reference to leaflet map object
       */
      _this.$emit('ready', _this.mapObject);
    });
  },
  render: function render() {
    return null;
  }
};

/* script */
var __vue_script__$5 = script$5;
/* template */

/* style */

var __vue_inject_styles__$5 = undefined;
/* scoped */

var __vue_scope_id__$5 = undefined;
/* module identifier */

var __vue_module_identifier__$5 = undefined;
/* functional template */

var __vue_is_functional_template__$5 = undefined;
/* style inject */

/* style inject SSR */

/* style inject shadow dom */

var __vue_component__$5 = /*#__PURE__*/__vue_normalize____default['default']({}, __vue_inject_styles__$5, __vue_script__$5, __vue_scope_id__$5, __vue_is_functional_template__$5, __vue_module_identifier__$5, false, undefined, undefined, undefined);

//
/**
 * Load tiles from a map server and display them accordingly to map zoom, center and size
 */

var script$4 = {
  name: 'LTileLayer',
  mixins: [vue2Leaflet.TileLayerMixin, vue2Leaflet.OptionsMixin],
  props: {
    url: {
      type: String,
      "default": null
    },
    tileLayerClass: {
      type: Function,
      "default": leaflet.tileLayer
    }
  },
  mounted: function mounted() {
    var _this = this;

    var options = vue2Leaflet.optionsMerger(this.tileLayerOptions, this);
    this.mapObject = this.$attrs.boundary ? leaflet.TileLayer.BoundaryCanvas.createFromLayer(this.tileLayerClass(this.url, options), {
      boundary: this.$attrs.boundary,
      trackAttribution: true
    }) : this.tileLayerClass(this.url, options);
    leaflet.DomEvent.on(this.mapObject, this.$listeners);
    vue2Leaflet.propsBinder(this, this.mapObject, this.$options.props);
    this.parentContainer = vue2Leaflet.findRealParent(this.$parent);
    this.parentContainer.addLayer(this, !this.visible);
    this.$nextTick(function () {
      /**
       * Triggers when the component is ready
       * @type {object}
       * @property {object} mapObject - reference to leaflet map object
       */
      _this.$emit('ready', _this.mapObject);
    });
  }
};

/* script */
var __vue_script__$4 = script$4;
/* template */

var __vue_render__$2 = function __vue_render__() {
  var _vm = this;

  var _h = _vm.$createElement;

  var _c = _vm._self._c || _h;

  return _c('div');
};

var __vue_staticRenderFns__$2 = [];
/* style */

var __vue_inject_styles__$4 = undefined;
/* scoped */

var __vue_scope_id__$4 = undefined;
/* module identifier */

var __vue_module_identifier__$4 = undefined;
/* functional template */

var __vue_is_functional_template__$4 = false;
/* style inject */

/* style inject SSR */

/* style inject shadow dom */

var __vue_component__$4 = /*#__PURE__*/__vue_normalize____default['default']({
  render: __vue_render__$2,
  staticRenderFns: __vue_staticRenderFns__$2
}, __vue_inject_styles__$4, __vue_script__$4, __vue_scope_id__$4, __vue_is_functional_template__$4, __vue_module_identifier__$4, false, undefined, undefined, undefined);

var findLeafletParent = function findLeafletParent(firstVueParent) {
  var found = false;

  while (firstVueParent && !found) {
    if (firstVueParent.mapObject === undefined || !(firstVueParent.mapObject instanceof leaflet.Map)) {
      firstVueParent = firstVueParent.$parent;
    } else {
      found = true;
    }
  }

  return firstVueParent;
};

/**
 * Display a popup on the map
 */

var script$3 = {
  name: 'LPopup',
  mixins: [vue2Leaflet.PopperMixin, vue2Leaflet.OptionsMixin],
  props: {
    latLng: {
      type: [Object, Array],
      custom: true,
      "default": function _default() {
        return [];
      }
    }
  },
  watch: {
    options: function options(newVal, oldVal) {
      var _this = this;

      this.$nextTick(function () {
        _this.mapObject.update();
      });
    }
  },
  mounted: function mounted() {
    var _this2 = this;

    var options = vue2Leaflet.optionsMerger(this.popperOptions, this);
    this.mapObject = leaflet.popup(options);
    this.leafletObject = findLeafletParent(this.$parent).mapObject;
    this.parentContainer = vue2Leaflet.findRealParent(this.$parent);
    leaflet.DomEvent.on(this.mapObject, this.$listeners);
    vue2Leaflet.propsBinder(this, this.mapObject, this.$options.props);
    this.mapObject.setContent(this.content || this.$el);

    if (!lodashEs.isEmpty(this.latLng)) {
      this.mapObject.setLatLng(this.latLng);
      this.openPopup();
    }

    this.parentContainer.mapObject.bindPopup && this.parentContainer.mapObject.bindPopup(this.mapObject);
    this.$nextTick(function () {
      /**
       * Triggers when the component is ready
       * @type {object}
       * @property {object} mapObject - reference to leaflet map object
       */
      _this2.$emit('ready', _this2.mapObject);
    });
  },
  methods: {
    openPopup: function openPopup() {
      if (!lodashEs.isEmpty(this.latLng)) this.mapObject.openOn(this.leafletObject);
    },
    closePopup: function closePopup() {
      this.leafletObject.closePopup();
    },
    setLatLng: function setLatLng(newVal, oldVal) {
      if (!lodashEs.isEmpty(newVal)) {
        this.mapObject.setLatLng(newVal).update();
        this.openPopup();
      } else {
        this.closePopup();
      }
    }
  },
  beforeDestroy: function beforeDestroy() {
    if (this.parentContainer) {
      this.closePopup();

      if (this.parentContainer.unbindPopup) {
        this.parentContainer.unbindPopup();
      } else if (this.parentContainer.mapObject && this.parentContainer.mapObject.unbindPopup) {
        this.parentContainer.mapObject.unbindPopup();
      }
    }
  }
};

/* script */
var __vue_script__$3 = script$3;
/* template */

/* style */

var __vue_inject_styles__$3 = undefined;
/* scoped */

var __vue_scope_id__$3 = undefined;
/* module identifier */

var __vue_module_identifier__$3 = undefined;
/* functional template */

var __vue_is_functional_template__$3 = undefined;
/* style inject */

/* style inject SSR */

/* style inject shadow dom */

var __vue_component__$3 = /*#__PURE__*/__vue_normalize____default['default']({}, __vue_inject_styles__$3, __vue_script__$3, __vue_scope_id__$3, __vue_is_functional_template__$3, __vue_module_identifier__$3, false, undefined, undefined, undefined);

//
var script$2 = {
  name: 'LManager',
  props: ['layers', 'controls'],
  computed: {
    computedControls: function computedControls() {
      return vueLeafletHelper.toMapControls(this.controls);
    },
    computedLayers: function computedLayers() {
      return vueLeafletHelper.toMapLayers(this.layers);
    }
  },
  methods: {
    onLayerReady: function onLayerReady(layerObject, layer, k) {
      layerObject._id = layer.id;
    }
  }
};

/* script */
var __vue_script__$2 = script$2;
/* template */

var __vue_render__$1 = function __vue_render__() {
  var _vm = this;

  var _h = _vm.$createElement;

  var _c = _vm._self._c || _h;

  return _c('div', [_vm._l(_vm.computedControls, function (i, k) {
    return _c(i.component, _vm._b({
      key: "control-" + k,
      tag: "component"
    }, 'component', i, false));
  }), _vm._v(" "), _vm._l(_vm.computedLayers, function (i, k) {
    return _c(i.component, _vm._b({
      key: "layer-" + k,
      tag: "component",
      on: {
        "ready": function ready(layer) {
          return _vm.onLayerReady(layer, i, k);
        }
      }
    }, 'component', i, false));
  }), _vm._v(" "), _vm._t("default")], 2);
};

var __vue_staticRenderFns__$1 = [];
/* style */

var __vue_inject_styles__$2 = undefined;
/* scoped */

var __vue_scope_id__$2 = undefined;
/* module identifier */

var __vue_module_identifier__$2 = undefined;
/* functional template */

var __vue_is_functional_template__$2 = false;
/* style inject */

/* style inject SSR */

/* style inject shadow dom */

var __vue_component__$2 = /*#__PURE__*/__vue_normalize____default['default']({
  render: __vue_render__$1,
  staticRenderFns: __vue_staticRenderFns__$1
}, __vue_inject_styles__$2, __vue_script__$2, __vue_scope_id__$2, __vue_is_functional_template__$2, __vue_module_identifier__$2, false, undefined, undefined, undefined);

/**
 * Add any custom component as a leaflet control-layers
 */

var script$1 = {
  name: 'LControlLayers',
  mixins: [vue2Leaflet.ControlMixin, vue2Leaflet.OptionsMixin],
  props: {
    collapsed: {
      type: Boolean,
      "default": true
    },
    autoZIndex: {
      type: Boolean,
      "default": false
    },
    hideSingleBase: {
      type: Boolean,
      "default": false
    },
    sortLayers: {
      type: Boolean,
      "default": false
    },
    sortFunction: {
      type: Function,
      "default": undefined
    }
  },
  mounted: function mounted() {
    var _this = this;

    var options = vue2Leaflet.optionsMerger(Object.assign({}, this.controlOptions, {
      collapsed: this.collapsed,
      autoZIndex: this.autoZIndex,
      hideSingleBase: this.hideSingleBase,
      sortLayers: this.sortLayers,
      sortFunction: this.sortFunction
    }), this);
    this.mapObject = leaflet.control.layers(null, null, options);
    this.parentContainer = findLeafletParent(this.$parent);
    vue2Leaflet.propsBinder(this, this.mapObject, this.$options.props);
    this.parentContainer.registerLayerControl(this);
    this.$nextTick(function () {
      /**
       * Triggers when the component is ready
       * @type {object}
       * @property {object} mapObject - reference to leaflet map object
       */
      _this.$emit('ready', _this.mapObject);
    });
  },
  methods: {
    addLayer: function addLayer(layer) {
      if (layer.layerType === 'base') {
        this.mapObject.addBaseLayer(layer.mapObject, layer.name);
      } else if (layer.layerType === 'overlay') {
        this.mapObject.addOverlay(layer.mapObject, layer.name);
      }
    },
    removeLayer: function removeLayer(layer) {
      this.mapObject.removeLayer(layer.mapObject);
    }
  },
  render: function render() {
    return null;
  }
};

/* script */
var __vue_script__$1 = script$1;
/* template */

/* style */

var __vue_inject_styles__$1 = undefined;
/* scoped */

var __vue_scope_id__$1 = undefined;
/* module identifier */

var __vue_module_identifier__$1 = undefined;
/* functional template */

var __vue_is_functional_template__$1 = undefined;
/* style inject */

/* style inject SSR */

/* style inject shadow dom */

var __vue_component__$1 = /*#__PURE__*/__vue_normalize____default['default']({}, __vue_inject_styles__$1, __vue_script__$1, __vue_scope_id__$1, __vue_is_functional_template__$1, __vue_module_identifier__$1, false, undefined, undefined, undefined);

//
var script = {
  name: 'LPopupContent',
  props: {
    loading: {
      type: Boolean,
      "default": false
    },
    content: {
      type: [String, Object],
      "default": ''
    },
    data: {
      type: Object
    },
    actions: {
      type: Array,
      "default": []
    }
  },
  methods: {
    isString: lodashEs.isString
  }
};

/* script */
var __vue_script__ = script;
/* template */

var __vue_render__ = function __vue_render__() {
  var _vm = this;

  var _h = _vm.$createElement;

  var _c = _vm._self._c || _h;

  return _c('div', [!_vm.loading ? _c('div', [_vm._v("Loading...")]) : _c('div', [_vm.isString(_vm.content) ? _c('div', {
    domProps: {
      "innerHTML": _vm._s(_vm.content)
    }
  }) : _c('div'), _vm._v(" "), _vm._t("default")], 2)]);
};

var __vue_staticRenderFns__ = [];
/* style */

var __vue_inject_styles__ = undefined;
/* scoped */

var __vue_scope_id__ = undefined;
/* module identifier */

var __vue_module_identifier__ = undefined;
/* functional template */

var __vue_is_functional_template__ = false;
/* style inject */

/* style inject SSR */

/* style inject shadow dom */

var __vue_component__ = /*#__PURE__*/__vue_normalize____default['default']({
  render: __vue_render__,
  staticRenderFns: __vue_staticRenderFns__
}, __vue_inject_styles__, __vue_script__, __vue_scope_id__, __vue_is_functional_template__, __vue_module_identifier__, false, undefined, undefined, undefined);

var index = {
  install: function install(Vue) {
    Vue.component('l-map', vue2Leaflet.LMap);
    Vue.component('l-tile-layer', __vue_component__$4);
    Vue.component('l-marker', vue2Leaflet.LMarker);
    Vue.component('l-cirlce', vue2Leaflet.LCircle);
    Vue.component('l-geojson', vue2Leaflet.LGeoJson);
    Vue.component('l-feature-group', vue2Leaflet.LFeatureGroup);
    Vue.component('l-popup', __vue_component__$3);
    Vue.component('l-wms-tile-layer', vue2Leaflet.LWMSTileLayer);
    Vue.component('l-draw-toolbar', LDrawToolbar__default['default']);
    Vue.component('l-control-layers', __vue_component__$1);
    Vue.component('l-control-legend', __vue_component__$7);
    Vue.component('l-control-print', __vue_component__$6);
    Vue.component('l-control-fullscreen', __vue_component__$5);
    Vue.component('l-manager', __vue_component__$2);
    Vue.component('l-popup-content', __vue_component__);
  }
};

Object.defineProperty(exports, 'LCircle', {
    enumerable: true,
    get: function () {
        return vue2Leaflet.LCircle;
    }
});
Object.defineProperty(exports, 'LGeoJson', {
    enumerable: true,
    get: function () {
        return vue2Leaflet.LGeoJson;
    }
});
Object.defineProperty(exports, 'LMap', {
    enumerable: true,
    get: function () {
        return vue2Leaflet.LMap;
    }
});
Object.defineProperty(exports, 'LMarker', {
    enumerable: true,
    get: function () {
        return vue2Leaflet.LMarker;
    }
});
Object.defineProperty(exports, 'LWMSTileLayer', {
    enumerable: true,
    get: function () {
        return vue2Leaflet.LWMSTileLayer;
    }
});
exports.LControlLayers = __vue_component__$1;
exports.LPopup = __vue_component__$3;
exports.default = index;
