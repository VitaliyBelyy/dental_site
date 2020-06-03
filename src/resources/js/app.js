import './bootstrap';
import Vue from 'vue';
import Vuetify from 'vuetify';
import ru from 'vuetify/es5/locale/ru';
import Vuelidate from 'vuelidate';
import Cookies from "js-cookie";
import VueScrollTo  from "vue-scrollto";
import CheckView from 'vue-check-view';
import VueSimpleSVG from 'vue-simple-svg';
import moment from 'moment';

moment.locale('ru');

const ignoreMessage = "The .native modifier for v-on is only valid on components but it was used on <div>.";
Vue.config.warnHandler = function (msg, vm, trace) {
    if (msg === ignoreMessage) {
        msg = null;
        vm = null;
        trace = null;
    }
};

// Vuex store
import store from '@/js/store';

// Router configuration
import router from '@/js/router';

// Vue filters
import filters from '@/js/filters';

// Root component file
import App from '@/js/App.vue';

import HttpClient from '@/js/api/HttpClient';

window.httpClient = new HttpClient();

const token = Cookies.get('access_token') || '';

// Bind access_token to requests if user was authorized
if (token) {
    window.httpClient.bindToken(token);
}

filters.init();

Vue.use(Vuetify, {
    lang: {
        locales: { ru },
        current: 'ru'
    }
})

Vue.use(Vuelidate);

Vue.use(VueScrollTo);

Vue.use(CheckView);

Vue.use(VueSimpleSVG);

Vue.config.productionTip = false;

new Vue({
    vuetify: new Vuetify(),
    store,
    router,
    render: h => h(App)
}).$mount('#app');
