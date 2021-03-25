<template>
    <vue-suggestion
        :id="field.name"
        v-model="item"
        :itemTemplate="itemTemplate"
        :items="items"
        :placeholder="field.placeholder || field.name"
        :setLabel="setLabel"
        :minLen="1"
        inputClasses="w-full form-control form-input form-input-bordered"
        @changed="inputChange"
        @selected="itemSelected"
        :loading="loading"
        autocomplete="off">
    </vue-suggestion>
</template>

<script>
    import {FormField, HandlesValidationErrors} from 'laravel-nova'
    import itemTemplate from './ItemTemplate.vue'
    import _ from 'lodash'
    import {EsriProvider, providerMappings} from '@ttungbmt/leaflet-search';

    export default {
        mixins: [FormField, HandlesValidationErrors],

        props: ['resourceName', 'resourceId', 'field'],

        data: () => ({
            minLen: 1,
            debouncer: null,
            item: {},
            items: [],
            itemTemplate,
            loading: false,
            geosearchOptions: {
                provider: new EsriProvider(),
            },
        }),

        watch: {
            item: {
                deep: true,
                handler() {
                    this.value = this.item.name || this.value
                },
            },
        },

        created() {
            const {searchProvider, searchProviderKey, searchParams = {}, debounce = 500} = this.field

            this.debouncer = _.debounce(callback => callback(), debounce)

            const providerOptions = {params: searchParams};

            if (typeof searchProviderKey !== 'undefined') {
                providerOptions.params.key = searchProviderKey
            }

            if(searchProvider && _.includes(_.keys(providerMappings), searchProvider)){
                this.geosearchOptions.provider = new providerMappings[searchProvider](providerOptions)
            }
        },

        mounted() {
            this.setInitialValue()
        },

        methods: {
            setInitialValue() {
                this.value = this.field.value || ''
                this.item = {id: 0, label: this.value}
            },
            async fetchPlaces() {
                if (this.value.length < this.minLen) return null;

                this.loading = true

                try {
                    const results = await this.geosearchOptions.provider.search({query: this.value})

                    this.items = _.chain(results).take(5).map((v, k) => ({
                        ...v,
                        id: k,
                    })).value()

                } catch(e) {
                    console.warn(e)
                    this.$toasted.show(
                        this.__('Error fetching data. Please check your logs'),
                        {type: 'error'}
                    );
                } finally {
                    this.loading = false
                }
            },
            itemSelected(item) {
                this.item = item

                this.$emit('select', item)
            },
            setLabel(item) {
                return item.label
            },
            inputChange(text) {
                this.value = text

                this.debouncer(() => {
                    this.fetchPlaces(text)
                    this.$emit('input', text)
                })
            },
        },
    }
</script>
