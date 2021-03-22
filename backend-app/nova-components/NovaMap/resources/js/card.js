import VueLodash from 'vue-lodash'
import VueLeaflet from '@ttungbmt/vue-leaflet'
import '@ttungbmt/vue-leaflet/dist/vue-leaflet.css'
import _ from 'lodash-es'

Nova.booting((Vue, router, store) => {
    Vue.use(VueLodash, { lodash: _ })
    Vue.use(VueLeaflet)
    Vue.component('nova-map-card', require('./components/Card'))
})
