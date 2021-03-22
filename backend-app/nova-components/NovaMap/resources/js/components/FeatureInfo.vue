<template>
    <div>
        <l-popup v-if="popup.data" :lat-lng="popup.latlng" :options="_.get(popup, 'config.options', {})">
            <div v-if="contentLoading">
                Loading...
            </div>
            <div v-else>
                <div class="flex absolute" style="top: 0; right: 20px">
                    <button @click="onPrevious(popupIndex-1)" v-if="features[popupIndex-1]" class="btn-previous"><i class="fas fa-chevron-left"></i></button>
                    <button @click="onNext(popupIndex+1)" v-if="features[popupIndex+1]" class="btn-next"><i class="fas fa-chevron-right"></i></button>
                </div>
                <div v-html="popup.html"></div>
                <div v-if="popup.config.actions" class="pop-actions flex">
                    <div v-for="(a, k) in popup.config.actions">
                        <div v-if="a.type === 'modal'" class="action-item p-1">
                            <button @click="openModal(`modal-${k}`)" style="outline: none;">{{a.title}}</button>
                            <nv-modal :ref="`modal-${k}`" :request="{method: 'post', url: a.url, data: popup.data}">
                            </nv-modal>
                        </div>
                        <div v-if="a.type === 'link'" class="action-item p-1">
                            <a :href="urlTemplate(a.url, popup.data)" target="_blank" class="cursor-pointer">{{a.title}}</a>
                        </div>
                    </div>
                </div>

            </div>
        </l-popup>
    </div>

</template>

<script>
    import {getUrlLayer, urlTemplate} from '@ttungbmt/vue-leaflet-helper'
    import Modal from './Modal'
    import _ from 'lodash-es'
    import axios from 'axios'

    export default {
        name: 'FeatureInfo',
        props: ['layers'],
        components: {
            'nv-modal': Modal
        },
        data() {
            return {
                features: [],
                popupIndex: 0,
                contentLoading: false,
                loading: true,
            }
        },
        watch: {
            contentLoading(newVal){
                if(newVal){
                    document.querySelector('.leaflet-container').style.cursor = 'progress'
                } else {
                    document.querySelector('.leaflet-container').style.cursor = 'inherit'
                }
            }
        },
        computed: {
            popup(){
                return _.get(this.features, `${this.popupIndex}`, {})
            },
            style(){
                return _.get(this.popup, `config.style`, {minWidth: '250px'})
            }
        },
        methods: {
            urlTemplate,
            openModal(name) {
                this.$refs[name][0].show()
            },
            onPrevious(index){
                this.popupIndex = index
            },
            onNext(index){
                if(this.features[index].data){
                    this.popupIndex = index
                } else {
                    this.fetchFeatures(this.features, index)
                }
            },
            fetchFeatures(features, startIndex = 0){
                this.contentLoading = true
                fetchFeatures(features,
                    (feature, index, resp) => {
                        this.$nextTick(() => {
                            this.features[index] = {
                                ...this.features[index],
                                ...resp,
                            }

                            this.popupIndex = index

                            this.contentLoading = false
                        })
                    },
                    ( idxs) => {
                        this.$nextTick(() => {
                            this.features = _.reject(this.features, (v, k) => _.includes(idxs, k))
                            this.updateIndex()
                            this.contentLoading = false
                        })
                    },
                    startIndex
                )
            },
            updateIndex(){
                this.features.map((f, k) => {
                    if(this.popupIndex === this.features[k].index) this.popupIndex = k

                    if(this.features[k].index !== k){
                        this.features[k].index = k
                    }
                })
            }
        },
        mounted() {
            this.$parent.mapObject.on('click', ({target, latlng}) => {
                this.features = featuresHavePopup(target, this.layers, latlng)
                this.fetchFeatures(this.features)
            })
        },

    }

    const featuresHavePopup = (map, layers, latlng) => {
        let features = [],
            layerIds = _.chain(layers).filter(l => l.popup).map('id').value()

        map.eachLayer(l => {
            let id = l._id,
                layer = _.find(layers, {id})

            if (l._wmsVersion && _.includes(layerIds, id)) features.push({
                config: layer.popup,
                url: getUrlLayer(latlng, l),
                zIndex: _.get(layer, 'options.zIndex', 10),
                latlng
            })
        })

        return _.orderBy(features, 'zIndex', 'desc').map((f, k) => Object.assign(f, {index: k}))
    }

    const fetchFeatures = async (items, foundCb, notFoundCb, startIndex = 0) => {
        let featureItems =_.cloneDeep(items),
            notFoundIdxs = []

        try {
            let k = 0
            for (let i of featureItems) {
                if(k >= startIndex) {
                    const {data} = await axios.get(i.url)

                    let geojson = _.get(data, 'features.0'),
                        featureId = _.get(geojson, 'properties.id'),
                        properties = _.get(geojson, 'properties'),
                        url = _.get(i, 'config.url')

                    if (featureId && url) {
                        const {data} = await axios.post(url, {
                            ...i,
                            properties
                        })

                        foundCb(i, k, data)
                        break
                    } else {
                        notFoundIdxs.push(k)
                    }
                }

                k++
            }

            notFoundCb && notFoundCb(notFoundIdxs)
        } catch (e) {
            console.error(e)
        }
    }
</script>
