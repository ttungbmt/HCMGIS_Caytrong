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
    import L from 'leaflet'
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
                            return this.$emit('markerUpdated', {
                                layer: e.layer,
                                id: e.layer._id,
                                type: 'marker',
                                data: [latlng.lat, latlng.lng]
                            })
                        })
                    }


                    if (layerObject instanceof L.Path || layerObject instanceof L.GeoJSON) {
                        layerObject.on('pm:edit', e => this.$emit('markerUpdated', {
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

                            if (!control.drawMultiple) {
                                this.map.pm.disableGlobalEditMode()

                                // target.eachLayer(layer1 => {
                                //     if (hasIn(layer1, 'pm.getShape') && !isEqual(layer1._leaflet_id, layer._leaflet_id)) {
                                //         let shape1 = layer1.pm.getShape(),
                                //             shape = layer.pm.getShape(),
                                //             types = ['Polygon', 'Rectangle']
                                //
                                //         if (
                                //             (shape1 === 'Marker' && shape === 'Marker') ||
                                //             (includes(types, shape1) && includes(types, shape))
                                //         ) {
                                //             this.map.removeLayer(layer1)
                                //         }
                                //     }
                                // })

                                this.map.removeLayer(layer)
                                this.$emit('markerCreated', {layer, shape})
                            }

                        },
                        ['pm:remove']: ({layer, shape, target}) => {
                            if (!control.drawMultiple) {
                                this.map.pm.disableGlobalRemovalMode()
                                this.$emit('markerRemoved', {layer, target})
                            }

                        }
                    }
                }


                return {}
            },
        }
    }
</script>