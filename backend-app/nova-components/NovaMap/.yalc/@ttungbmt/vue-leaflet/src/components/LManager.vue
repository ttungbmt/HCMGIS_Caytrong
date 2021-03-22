<template>
    <div>
        <component :is="i.component" v-bind="i" v-for="(i, k) in computedControls" :key="`control-${k}`"></component>
        <component :is="i.component" v-bind="i" v-for="(i, k) in computedLayers" :key="`layer-${k}`" @ready="layer => onLayerReady(layer, i, k)"></component>
        <slot />
    </div>
</template>
<script>
    import {toMapLayers, toMapControls} from '@ttungbmt/vue-leaflet-helper'

    export default {
        name: 'LManager',
        props: ['layers', 'controls'],
        computed: {
            computedControls(){
                return toMapControls(this.controls)
            },
            computedLayers(){
                return toMapLayers(this.layers)
            },
        },
        methods: {
            onLayerReady(layerObject, layer, k){
                layerObject._id = layer.id
            }
        }
    }
</script>