import { LMap, LMarker, LCircle, LGeoJson, LWMSTileLayer, LFeatureGroup } from 'vue2-leaflet'
import LDrawToolbar from 'vue2-leaflet-draw-toolbar'

import 'leaflet-boundary-canvas'
import './styles/index.scss'

import LControlLegend from './components/LControlLegend.vue'
import LControlPrint from './components/LControlPrint.vue'
import LControlFullscreen from './components/LControlFullscreen.vue'
import LTileLayer from './components/LTileLayer.vue'
import LPopup from './components/LPopup.vue'
import LManager from './components/LManager.vue'
import LControlLayers from './components/LControlLayers.vue'
import LPopupContent from './components/LPopupContent.vue'

export default {
    install(Vue){
        Vue.component('l-map', LMap);
        Vue.component('l-tile-layer', LTileLayer);
        Vue.component('l-marker', LMarker);
        Vue.component('l-cirlce', LCircle);
        Vue.component('l-geojson', LGeoJson);
        Vue.component('l-feature-group', LFeatureGroup);
        Vue.component('l-popup', LPopup);
        Vue.component('l-wms-tile-layer', LWMSTileLayer);
        Vue.component('l-draw-toolbar', LDrawToolbar);

        Vue.component('l-control-layers', LControlLayers);
        Vue.component('l-control-legend', LControlLegend);
        Vue.component('l-control-print', LControlPrint);
        Vue.component('l-control-fullscreen', LControlFullscreen);

        Vue.component('l-manager', LManager);
        Vue.component('l-popup-content', LPopupContent);
    }
}

export {
    LMap, LMarker, LCircle, LGeoJson, LPopup, LControlLayers, LWMSTileLayer
}