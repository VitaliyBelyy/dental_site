import './bootstrap';
import Vue from 'vue';
import Vuetify from 'vuetify';
import Vuelidate from 'vuelidate';
import Cookies from "js-cookie";

// Vuex store
import store from '@/js/store/index';

// Router configuration
import router from '@/js/router/index';

// Root component file
import App from '@/js/App.vue';

import HttpClient from '@/js/api/HttpClient';

window.httpClient = new HttpClient();

const token = Cookies.get('access_token') || '';

// Bind access_token to requests if user was authorized
if (token) {
    window.httpClient.bindToken(token);
}

Vue.use(Vuetify);

Vue.use(Vuelidate);

Vue.config.productionTip = false;

new Vue({
    vuetify: new Vuetify(),
    store,
    router,
    render: h => h(App)
}).$mount('#app');
