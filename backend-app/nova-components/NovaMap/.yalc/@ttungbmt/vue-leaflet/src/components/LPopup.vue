<script>
    import { optionsMerger, propsBinder, findRealParent, PopperMixin as Popper,  OptionsMixin as Options} from 'vue2-leaflet';
    import { popup, DomEvent, Map, Layer } from 'leaflet';
    import {isEmpty} from 'lodash-es'
    import {findLeafletParent} from '../utils/utils';

    /**
     * Display a popup on the map
     */
    export default {
        name: 'LPopup',
        mixins: [Popper, Options],
        props: {
            latLng: {
                type: [Object, Array],
                custom: true,
                default: () => [],
            },
        },
        watch: {
            options(newVal, oldVal){
                this.$nextTick(() => {
                    this.mapObject.update()
                })
            }
        },
        mounted() {
            const options = optionsMerger(this.popperOptions, this);
            this.mapObject = popup(options);
            this.leafletObject = findLeafletParent(this.$parent).mapObject;
            this.parentContainer = findRealParent(this.$parent);

            DomEvent.on(this.mapObject, this.$listeners);
            propsBinder(this, this.mapObject, this.$options.props);
            this.mapObject.setContent(this.content || this.$el);

            if(!isEmpty(this.latLng)){
                this.mapObject.setLatLng(this.latLng);
                this.openPopup()
            }

            this.parentContainer.mapObject.bindPopup && this.parentContainer.mapObject.bindPopup(this.mapObject);

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
            openPopup(){
                if(!isEmpty(this.latLng)) this.mapObject.openOn(this.leafletObject);
            },
            closePopup(){
                this.leafletObject.closePopup()
            },
            setLatLng(newVal, oldVal){
                if(!isEmpty(newVal)){
                    this.mapObject.setLatLng(newVal).update()
                    this.openPopup()
                } else {
                    this.closePopup()
                }
            }
        },
        beforeDestroy() {
            if (this.parentContainer) {
                this.closePopup()

                if (this.parentContainer.unbindPopup) {
                    this.parentContainer.unbindPopup();
                } else if (
                    this.parentContainer.mapObject &&
                    this.parentContainer.mapObject.unbindPopup
                ) {
                    this.parentContainer.mapObject.unbindPopup();
                }
            }
        },
    };
</script>