/**
 * First, we will load all of this project's Javascript utilities and other
 * dependencies. Then, we will be ready to develop a robust and powerful
 * application frontend using useful Laravel and JavaScript libraries.
 */

import './bootstrap';

import Vue from 'vue';
import Vuetify from 'vuetify';
import Vuelidate from 'vuelidate';
import Vuex from 'vuex';
import colors from 'vuetify/lib/util/colors';

// Route information for vue router
import Routes from '@/js/routes.js';

// Component File
import App from '@/js/views/App';

// Layout
import Default from '@/js/layouts/Default';
import NoSidebar from '@/js/layouts/NoSidebar';
import http from '@/js/http';

Vue.use(http);
Vue.use(Vuetify);
Vue.use(Vuelidate);

Vue.component('default-layout', Default);
Vue.component('no-sidebar-layout', NoSidebar);

import store from './store.js';

import helpers from './helpers.js';
Vue.mixin({
    methods: helpers
})

const app = new Vue({
    el: '#app',
    store: store,
    router: Routes,
    vuetify: new Vuetify({
        theme: {
            themes: {
                light: {
                    primary: '#f6b733'//colors.grey.darken3
                }
            }
        }
    }),
    render: h => h(App)
});

export default app;