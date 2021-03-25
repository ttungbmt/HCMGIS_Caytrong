<script>
	import { optionsMerger, propsBinder, ControlMixin, OptionsMixin, findRealParent } from 'vue2-leaflet';
	import { control } from 'leaflet';
	import 'leaflet.fullscreen'
	import 'leaflet.fullscreen/Control.FullScreen.css'
	import screenfull from 'screenfull'

    if(!window['screenfull']) window['screenfull'] = screenfull

	/**
	 * Add any custom component as a leaflet control-fullscrren
	 */
	export default {
		name: 'LControlFullscreen',
		mixins: [ControlMixin, OptionsMixin],
		props: {
			title: { type: String, default: 'Full Screen' },
			titleCancel: { type: String, default: 'Exit Full Screen' },
			forceSeparateButton: { type: Boolean, default: true },
			forcePseudoFullscreen: { type: Boolean, default: false },
			fullscreenElement: { type: Boolean, default: false }
		},
		mounted() {
			const options = optionsMerger({ ...this.controlOptions}, this);

			this.mapObject = control.fullscreen(options);
			propsBinder(this, this.mapObject, this.$options.props);
            this.parentContainer = findRealParent(this.$parent);
			this.mapObject.addTo(this.parentContainer.mapObject);
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