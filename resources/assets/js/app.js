require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';

import {routes} from './router/routes';
import store from './store';
import MainApp from './components/MainApp.vue';
import {initialize} from './helpers/general';
import { library, config } from '@fortawesome/fontawesome-svg-core';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import Switches from 'vue-switches';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';
import VuePhoneNumberInput from 'vue-phone-number-input';
import * as LocationPicker from 'vue2-location-picker';
import * as VueGoogleMaps from 'vue2-google-maps';

Vue.use(VueRouter)
Vue.use(Vuex)
Vue.use(LocationPicker, {
    installComponents: false, // If true, create it globally
})
Vue.use(VueGoogleMaps, {
    load: {
      key: "AIzaSyBGQ4RFLbX_ObaSpYDq5moqE5_bcVDQbnI",
      libraries: "places" // necessary for places input
    }
});

const router = new VueRouter({
    routes,
    mode: 'history'
})

config.autoAddCss = false
library.add(fas)
Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.component('switches', Switches)
Vue.component('vue-phone-number-input', VuePhoneNumberInput);

initialize(store, router);

const app = new Vue({
    el: '#app',
    router,
    store,
    components: {
        'main-app': MainApp        
    }
});
