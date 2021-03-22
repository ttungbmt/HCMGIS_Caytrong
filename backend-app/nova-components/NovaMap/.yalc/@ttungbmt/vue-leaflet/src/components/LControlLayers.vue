<script>
    import { optionsMerger, propsBinder, ControlMixin, OptionsMixin as Options } from 'vue2-leaflet';
    import { control } from 'leaflet';
    import {findLeafletParent} from '../utils/utils';
    /**
     * Add any custom component as a leaflet control-layers
     */
    export default {
        name: 'LControlLayers',
        mixins: [ControlMixin, Options],
        props: {
            collapsed: {
                type: Boolean,
                default: true,
            },
            autoZIndex: {
                type: Boolean,
                default: false,
            },
            hideSingleBase: {
                type: Boolean,
                default: false,
            },
            sortLayers: {
                type: Boolean,
                default: false,
            },
            sortFunction: {
                type: Function,
                default: undefined,
            },
        },
        mounted() {
            const options = optionsMerger(
                {
                    ...this.controlOptions,
                    collapsed: this.collapsed,
                    autoZIndex: this.autoZIndex,
                    hideSingleBase: this.hideSingleBase,
                    sortLayers: this.sortLayers,
                    sortFunction: this.sortFunction,
                },
                this
            );
            this.mapObject = control.layers(null, null, options);
            this.parentContainer = findLeafletParent(this.$parent);

            propsBinder(this, this.mapObject, this.$options.props);
            this.parentContainer.registerLayerControl(this);
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
            addLayer(layer) {
                if (layer.layerType === 'base') {
                    this.mapObject.addBaseLayer(layer.mapObject, layer.name);
                } else if (layer.layerType === 'overlay') {
                    this.mapObject.addOverlay(layer.mapObject, layer.name);
                }
            },
            removeLayer(layer) {
                this.mapObject.removeLayer(layer.mapObject);
            },
        },
        render() {
            return null;
        },
    };
</script>