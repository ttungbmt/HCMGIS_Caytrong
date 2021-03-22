<template>
    <loading-card class="rounded flex flex-col overflow-hidden" :style="style" :loading="loading">
        <l-map class="z-10" ref="map" v-bind="mapOptions" v-if="!loading">
            <l-manager :layers="layers" :controls="controls"></l-manager>

            <feature-info :layers="layers"></feature-info>
        </l-map>
    </loading-card>
</template>

<script>
    import FeatureInfo from './FeatureInfo'
    import { nanoid } from 'nanoid'

    export default {
        props: [
            'card',
        ],
        components: {
            'feature-info': FeatureInfo
        },
        data() {
            return {
                loading: true,
                style: this.getStyle(),
                mapOptions: {
                    zoom: 11,
                    center: [10.240095, 106.373147],
                    options: {
                        zoomControl: false
                    }
                },
                controls: {
                    layers: {
                        position: 'topright',
                        autoZIndex: false
                    }
                },
                layers: []
            }
        },
        computed: {},
        mounted() {
            this.card.height === 'full' && this.handleResizeCard()
            this.card.configUrl && this.fetchConfig()
        },
        methods: {
            fetchConfig() {
                this.loading = true
                Nova.request().get(this.card.configUrl).then(({data}) => {
                    this.layers = data.layers.map(layer => ({id: nanoid(), ...layer}))
                    this.loading = false
                })
            },
            updateMapSize() {
                this.$nextTick(() => {
                    this.$refs.map.mapObject.invalidateSize()
                })
            },
            handleResizeCard() {
                let hContent = document.querySelector('.content').clientHeight,
                    hHeader = document.querySelector('.h-header').clientHeight,
                    subSize = 140 * (701 / hContent)

                this.style.height = (hContent - hHeader - subSize) + 'px'
            },
            getStyle() {
                let {style = {height: '300px'}, height} = this.card

                if (height && height !== 'full') style = Object.assign(style, {height})

                return style
            }
        }
    }
</script>
