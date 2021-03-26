import VueLodash from 'vue-lodash'
import VueSuggestion from 'vue-suggestion'
import VueLeaflet from '@ttungbmt/vue-leaflet'
import '@ttungbmt/vue-leaflet/dist/vue-leaflet.css'
import _ from 'lodash-es'

import VueTippy, { TippyComponent } from 'vue-tippy'
import 'tippy.js/themes/light.css';
import 'tippy.js/themes/light-border.css';
import 'tippy.js/themes/google.css';

Nova.booting((Vue, router, store) => {
    Vue.use(VueSuggestion)
    Vue.use(VueLodash, { lodash: _ })
    Vue.use(VueLeaflet)
    Vue.use(VueTippy);

    Vue.component('place-suggestion', require('./components/Place/PlaceSuggestion'))

    Vue.component('nova-map-card', require('./components/Card'))

    Vue.component('index-place', require('./components/Place/IndexField'))
    Vue.component('detail-place', require('./components/Place/DetailField'))
    Vue.component('form-place', require('./components/Place/FormField'))

    Vue.component('index-map', require('./components/Index/MapField'))
    Vue.component('detail-map', require('./components/Detail/MapField'))
    Vue.component('form-map', require('./components/Form/MapField'))

    Vue.component('tippy', TippyComponent)

    router.addRoutes([
        {
            name: 'nova-map',
            path: '/nova-map',
            component: require('./components/Tool'),
        },
    ])
})
