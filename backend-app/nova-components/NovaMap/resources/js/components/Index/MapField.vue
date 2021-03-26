<template>
    <div :class="`text-${field.textAlign}`">
        <template v-if="hasValue">
            <div class="flex cursor-pointer">
                <tippy v-bind="tippyOptions" class="index-map">
                    <template v-slot:trigger>
                        <button>
                            <svg class="map-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                    </template>

                    <l-map class="index-map-content" v-bind="mapOptions" style="width: 320px;height: 250px; margin: -5px -9px;" v-if="!loading">
                        <component :is="i.component" v-bind="i" v-for="(i, k) in computedLayers" :key="`layer-${k}`">
                            <l-popup v-if="i.component === 'l-marker' && i.latLng" :options="{minWidth: 180}">
                                <span class="font-semibold">{{__('Coordinates')}}</span>: {{i.latLng.join(',')}}
                            </l-popup>
                        </component>
                    </l-map>
                </tippy>
            </div>

        </template>
        <p v-else>&mdash;</p>
    </div>
</template>

<script>
    import {toMapLayers, getBounds} from '@ttungbmt/vue-leaflet-helper'
    import {cloneDeep} from "lodash-es";
    // import area from '@turf/area'

    export default {
        props: ['resourceName', 'field'],

        data(){
            return {
                tippyOptions: {
                    interactive: true,
                    theme: 'light',
                    placement: 'right',
                    arrow: true,
                    trigger: 'click', // mouseenter, click
                    animation: 'scale',
                    onShow: this.onShow,
                    onHidden: this.onHidden
                },
                mapOptions: this.getMapOptions(),

                controls: this.field.controls || {},
                loading: true
            }
        },

        computed: {
            /**
             * Determine if the field has a value other than null.
             */
            hasValue() {
                return this.field.value.length
            },
            computedLayers(){
                return toMapLayers(this.field.layers.filter(l => l.active).concat(this.field.value))
            }
        },

        methods: {
            getMapOptions() {
                let mapOptions = cloneDeep(this.field.mapOptions)

                if (!mapOptions) return {}
                if (mapOptions.bounds) mapOptions.bounds = getBounds(mapOptions.bounds)

                return mapOptions
            },
            getArea(i){
                console.log(i)
                return ''
            },
            onShow(){
                this.loading = false
            },
            onHidden(){
                this.loading = true
            },
        }
    }
</script>

<style lang="scss" scoped>
    .index-map-content {
        border-radius: 5px;
    }

    button {outline: none}
</style>

