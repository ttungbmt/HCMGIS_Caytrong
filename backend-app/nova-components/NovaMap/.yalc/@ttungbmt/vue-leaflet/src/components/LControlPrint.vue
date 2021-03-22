<script>
	import { optionsMerger, propsBinder, findRealParent, ControlMixin, OptionsMixin } from 'vue2-leaflet';
	import { control, DomEvent} from 'leaflet';
	import 'leaflet.browser.print/dist/leaflet.browser.print'
	/**
	 * Add any custom component as a leaflet control-scale
	 */
	export default {
		name: 'LControlPrint',
		mixins: [ControlMixin, OptionsMixin],
		props: {

		},
		mounted() {
			const options = optionsMerger(
				{
					...this.controlOptions,
				},
				this
			);

			this.mapObject = control.browserPrint(options);
			propsBinder(this, this.mapObject, this.$options.props);
			this.mapObject.addTo(this.$parent.mapObject);

			DomEvent.on(this.$parent.mapObject, this.$listeners);

			this.$nextTick(() => {
				/**
				 * Triggers when the component is ready
				 * @type {object}
				 * @property {object} mapObject - reference to leaflet map object
				 */
				this.$emit('ready', this.mapObject);
			});

		},
		render() {
			return null;
		},
	};

</script>