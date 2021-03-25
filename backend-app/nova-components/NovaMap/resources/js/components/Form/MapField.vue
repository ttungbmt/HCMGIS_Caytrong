<template>
    <component :is="root.component" v-bind="root">
        <template :slot="root.slot">
            <l-map class="form-input-bordered z-10 mb-3" ref="map" v-bind="mapOptions" :style="mapStyle" v-if="showMap"
                   @ready="onReady">
                <l-manager :layers="layers" :controls="controls"
                           @markerCreated="onMarkerCreated"
                           @markerUpdated="onMarkerUpdated"
                           @markerRemoved="onMarkerRemoved"
                           @geojsonUpdated="onGeoJSONUpdated"
                           @geojsonRemoved="onGeoJSONRemoved"
                ></l-manager>

                <l-marker v-if="marker.latLng" v-bind="marker"></l-marker>
            </l-map>

            <div class="lh-text-auto-complete" v-if="field.searchable">
                <place-suggestion :field="field.searchable" @select="handleSelect"
                                  @input="handleInput"></place-suggestion>
            </div>

            <div v-for="(i, k) in value">
                <div v-if="showLatLngField && i.type === 'marker'" class="py-3">
                    <div v-if="viewType === 'detail'">
                        {{i.data.join(', ')}}
                    </div>
                    <div class="grid grid-cols-2 gap-4" v-else>
                        <div>
                            <input type="number"
                                   class="w-full form-control form-input form-input-bordered"
                                   :class="errorClasses"
                                   :placeholder="__('Latitude')"
                                   :value="i.data[0]"
                                   @input="e => handleChangeMarker(e, 0, k)"
                                   :min="0"
                                   :step="1e-18"
                            />
                        </div>
                        <div>
                            <input type="number"
                                   class="w-full form-control form-input form-input-bordered"
                                   :class="errorClasses"
                                   :placeholder="__('Longitude')"
                                   :value="i.data[1]"
                                   @input="e => handleChangeMarker(e, 1, k)"
                                   :min="0"
                                   :step="1e-18"
                            />
                        </div>
                    </div>
                </div>


                <div class="py-3" v-if="showGeoJSONField && i.type === 'geojson'">
                    <input type="text"
                           class="w-full form-control form-input form-input-bordered"
                           :class="errorClasses"
                           :placeholder="__('GeoJSON')"
                           :value="stringify(i.data)"
                           @input="e => handleChangeGeoJSON(e, 1, k)"
                    />
                </div>
            </div>
        </template>
    </component>
</template>

<script>
    import {FormField, HandlesValidationErrors} from 'laravel-nova'
    import {getBounds} from '@ttungbmt/vue-leaflet-helper'
    import _, {cloneDeep, defaults, pick, hasIn, findIndex, includes} from 'lodash-es'
    import L from 'leaflet'
    import {formatLayers} from '../../utils/utils';
    import {nanoid} from 'nanoid'
    import {getGeom, getCoords} from '@turf/invariant'
    import {ExtraMarkers} from 'leaflet'

    export default {
        mixins: [HandlesValidationErrors, FormField],

        props: ['viewType'],

        data() {
            const field = defaults(this.field, {
                mapStyle: 'height: 350px',
                showSearchBar: true,
                showLatLngField: true,
                showGeoJSONField: false,
                showMap: true,
                controls: {},
                geomTypes: [],
                icon: ExtraMarkers.icon({
                    icon: 'far fa-circle extra-marker-icon',
                    markerColor: 'cyan',
                }),
            })

            return {
                ...pick(field, ['mapStyle', 'showSearchBar', 'showLatLngField', 'showGeoJSONField', 'controls', 'geomTypes', 'showMap']),
                mapOptions: this.getMapOptions(),

                marker: {
                    latLng: null,
                    icon: field.icon
                }
            }
        },

        watch: {},

        computed: {
            root(){
                if(this.viewType === 'detail') {
                    return {
                        component: 'panel-item',
                        field: this.field,
                        slot: 'value'
                    }
                }

                return {
                    component: 'default-field',
                    field: this.field,
                    errors: this.errors,
                    fullWidthContent: !!this.field.fullWidth,
                    showHelpText: this.showHelpText,
                    slot: 'field'
                }
            },
            layers() {
                return formatLayers((this.field.layers || []).concat(this.value)).filter(v => {
                    if (hasIn(v, 'data')) {
                        if (v.type === 'marker' && v.data.length !== 2) return false
                        if (v.type === 'geojson' && v.data === 0) return false
                    }

                    return true
                })
            }
        },

        created() {
            L.Icon.Default = L.ExtraMarkers.Icon
            L.Icon.Default.mergeOptions({
                icon: 'far fa-circle extra-marker-icon',
                markerColor: 'orange',
            })
        },

        methods: {
            onReady() {
                this.map = this.$refs.map.mapObject
            },
            stringify(value) {
                return JSON.stringify(value)
            },
            getMapOptions() {
                let mapOptions = cloneDeep(this.field.mapOptions)

                if (!mapOptions) return {}
                if (mapOptions.bounds) mapOptions.bounds = getBounds(mapOptions.bounds)

                return mapOptions
            },
            handleSelect(item) {
                let latLng = [item.y, item.x],
                    geomTypes = _.chain(this.geomTypes).filter({type: 'Point'}).map('attribute').value(),
                    itemInndex = this.value.findIndex(v => _.includes(geomTypes, v.geom_field) && v.type === 'marker')

                if (itemInndex >= 0 && this.viewType !== 'detail') {
                    this.$set(this.value[itemInndex], 'data', latLng)
                } else {
                    this.marker.latLng = latLng
                }

                this.$set(this.mapOptions, 'center', latLng)
            },
            handleInput(text) {
                !text && this.$set(this.marker, 'latLng', null)
            },
            handleChangeMarker(e, index, pathIndex) {
                let value = parseFloat(e.target.value)

                if (value) this.$set(this.value[pathIndex].data, index, parseFloat(e.target.value))
                else this.$set(this.value[pathIndex], 'data', [])

                if (this.value[pathIndex].data.length === 2) this.$set(this.mapOptions, 'center', this.value[pathIndex].data)
            },
            handleChangeGeoJSON(e, index, pathIndex) {
                let value = e.target.value
                if (!value) return void undefined

                try {
                    this.$set(this.value[pathIndex].data, index, getGeom(JSON.parse(value)))
                } catch (e) {
                    alert('Invalid GeoJSON')
                }
            },
            setInitialValue() {
                this.value = this.field.value ? this.field.value.map(v => ({id: nanoid(), ...v})) : []
            },
            fill: function (formData) {
                formData.append(this.field.attribute, JSON.stringify(this.value) || []);
            },
            onMarkerCreated(e) {
                if (!this.controls.geoman.drawMultiple) this.value = []

                this.$nextTick(() => {
                    let geoJSON = e.layer.toGeoJSON(),
                        geomType = _.get(geoJSON, 'geometry.type'),
                        dbGeomType = _.chain(this.geomTypes).filter(v => includes(v.type, geomType)).last().value(),
                        attibute = _.get(dbGeomType, 'attribute'),
                        value = {
                            id: nanoid(),
                            active: true,
                            geom_field: attibute
                        }

                    if (e.layer instanceof L.Marker) {
                        let latLng = e.layer.getLatLng()
                        value.type = 'marker'
                        value.data = [latLng.lat, latLng.lng]
                    } else {
                        value.type = 'geojson'
                        if (includes(dbGeomType.type, 'Multi')) {
                            let geom = getGeom(geoJSON)
                            geom.type = dbGeomType.type
                            geom.coordinates = [getCoords(geom)]
                            value.data = geom
                        } else {
                            value.data = getGeom(geoJSON)
                        }
                    }

                    this.value.push(value)
                })
            },
            onMarkerUpdated(e) {
                let index = findIndex(this.value, {id: e.id})

                if (e.id && index >= 0) {
                    let index = findIndex(this.value, {id: e.id})
                    this.value[index].data = e.data
                } else {
                    console.warn(`Not found id`, e)
                }

            },
            onMarkerRemoved(e) {
                this.value = this.value.filter(v => e.layer._id !== v.id)
            },
            onGeoJSONUpdated(geojson) {
                this.geojson = JSON.stringify(geojson)
            },
            onGeoJSONRemoved() {
                this.geojson = ''
            },

        }
    }
</script>
