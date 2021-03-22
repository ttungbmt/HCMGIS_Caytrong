<template>
    <div ref="legend" style="display: none">
        <slot />

        <div class="leaflet-bar leaflet-legend leaflet-legend-expanded">
            <div class="leaflet-legend-title" v-if="title">{{title}}</div>
            <div class="legend-items" >
                <div class="legend-row" v-for="i in items">
                    <div class="leaflet-legend-html">
                        <div :class="`lengend-${i.type}`" :style="getStyle(i)"></div>
                    </div>
                    <div class="lengend-label">{{i.label}}</div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import {optionsMerger, propsBinder, ControlMixin, OptionsMixin} from 'vue2-leaflet';
    import {control, DomUtil} from 'leaflet';

    /*
    * Legend: https://bando.net.vn/wp-content/uploads/2020/02/Khi-Hau-VN.jpg
    * https://github.com/ptma/Leaflet.Legend
    * https://github.com/consbio/Leaflet.HtmlLegend
    * */

    export default {
        name: 'LControlLegend',
        mixins: [ControlMixin, OptionsMixin],
        props: {
        	items: Array,
			title: String
        },
        computed: {

        },
        mounted() {
            let $legend = this.$refs.legend.innerHTML

            const options = optionsMerger(
                {
                    ...this.controlOptions,
					title: this.title
                },
                this
            );

            this.mapObject = control(options);
            propsBinder(this, this.mapObject, this.$options.props);

            this.mapObject.onAdd = function (map) {
                let div = DomUtil.create('div', 'info legend')
                div.innerHTML = $legend
                return div
            }

            this.mapObject.addTo(this.$parent.mapObject);

            this.$nextTick(() => {
                /**
                 * Triggers when the component is ready
                 * @type {object}
                 * @property {object} mapObject - reference to leaflet map object
                 */
                this.$emit('ready', this.mapObject);
            });
        },
        methods: {
        	getStyle(i){
                let style = `background-color: ${i.fillColor};`
                if(i.color) style += `border: 1px solid ${i.color};`

                return style
            }
        }
    };
</script>

<style lang="scss">
    .leaflet-legend {
        background: #fff;

        .leaflet-legend-title {
            font-weight: 600;
            padding-bottom: 4px;
        }

        .legend-items {
            .legend-row {
                display: flex;
                padding: 2px 0;

                .lengend-rectangle {
                    width: 18px;
                    height: 18px;
                    background: white;
                }

                .lengend-label {
                    padding-left: 4px;
                }
            }
        }
    }

    .leaflet-legend-expanded {
        padding: 6px 10px 6px 6px;
        color: #333;
        background: #fff;
        max-height: 270px;
        overflow-y: auto;
    }
</style>

