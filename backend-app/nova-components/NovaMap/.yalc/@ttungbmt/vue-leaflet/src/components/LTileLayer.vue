<template>
    <div />
</template>

<script>
	import { optionsMerger, propsBinder, findRealParent, TileLayerMixin, OptionsMixin } from 'vue2-leaflet';
	import { TileLayer, tileLayer, DomEvent } from 'leaflet';
	/**
	 * Load tiles from a map server and display them accordingly to map zoom, center and size
	 */
	export default {
		name: 'LTileLayer',
		mixins: [TileLayerMixin, OptionsMixin],
		props: {
			url: {
				type: String,
				default: null,
			},
			tileLayerClass: {
				type: Function,
				default: tileLayer,
			},
		},
		mounted() {
			const options = optionsMerger(this.tileLayerOptions, this);
			const boundary = this.$attrs.boundary || options.boundary

			this.mapObject = boundary ? TileLayer.BoundaryCanvas.createFromLayer(this.tileLayerClass(this.url, options), {
				boundary,
				trackAttribution: true
			}) :  this.tileLayerClass(this.url, options);

			DomEvent.on(this.mapObject, this.$listeners);
			propsBinder(this, this.mapObject, this.$options.props);
			this.parentContainer = findRealParent(this.$parent);
			this.parentContainer.addLayer(this, !this.visible);
			this.$nextTick(() => {
				/**
				 * Triggers when the component is ready
				 * @type {object}
				 * @property {object} mapObject - reference to leaflet map object
				 */
				this.$emit('ready', this.mapObject);
			});
		},
	};
</script>