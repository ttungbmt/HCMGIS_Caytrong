<template>
    <div>
        <component :is="i.component" v-bind="i" v-for="(i, k) in computedControls" :key="`control-${k}`"
                   v-on="getControlListeners(i, k)"></component>
        <component :is="i.component" v-bind="i" v-for="(i, k) in computedLayers" :key="`layer-${k}`"
                   @ready="layer => onLayerReady(layer, i, k)" v-on="getLayerListeners(i, k)"></component>
        <slot/>
    </div>
</template>
<script>
    import {toMapLayers, toMapControls} from '@ttungbmt/vue-leaflet-helper'
    import {findRealParent} from 'vue2-leaflet'
    import L, {LayerGroup} from 'leaflet'
    import {getGeom} from "@turf/invariant";

    export default {
        name: 'LManager',
        props: ['layers', 'controls'],
        computed: {
            computedControls() {
                return toMapControls(this.controls)
            },
            computedLayers() {
                return toMapLayers(this.layers)
            },
        },
        mounted() {
            this.$nextTick(() => {
                this.map = findRealParent(this.$parent).mapObject;
            })
        },
        methods: {
            onLayerReady(layerObject, cLayer, k) {
                layerObject._id = cLayer.id

                let layer = this.layers[k]

                if (layer.geom_field) {
                    if (layerObject instanceof L.Marker) {
                        layerObject.on('pm:edit', e => {
                            let latlng = e.layer.getLatLng()
                            return this.$emit('shapeUpdated', {
                                layer: e.layer,
                                id: e.layer._id,
                                type: 'marker',
                                data: [latlng.lat, latlng.lng]
                            })
                        })
                    }


                    if (layerObject instanceof L.Path || layerObject instanceof L.GeoJSON) {
                        layerObject.on('pm:edit', e => this.$emit('shapeUpdated', {
                            layer: layerObject,
                            id: layerObject._id,
                            type: 'geojson',
                            data: getGeom(e.layer.toGeoJSON())
                        }))
                    }
                }
            },
            getLayerListeners(cLayer, index) {
                let layer = this.layers[index],
                    events = {}


                return events
            },
            getControlListeners(cControl, index) {
                let control = this.controls[cControl.name],
                    events = {}

                if (cControl.name === 'geoman') {

                    return {
                        ['pm:create']: ({layer, shape, target}) => {
                            if (!control.drawMultiple) this.map.pm.disableGlobalEditMode()
                            this.map.removeLayer(layer)
                            this.$emit('shapeCreated', {shape, layer})

                        },
                        ['pm:remove']: ({layer, shape, target}) => {
                            if (!control.drawMultiple) this.map.pm.disableGlobalRemovalMode()

                            if(!layer._id){
                                target.eachLayer(l => {
                                    (l._id && l.hasLayer && l.hasLayer(layer)) && this.$emit('shapeRemoved', {shape, layer: l})
                                })
                            }
                        }
                    }
                }


                return {}
            },
        }
    }
</script>