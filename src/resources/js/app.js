import './bootstrap';
import Vue from 'vue';
import Vuetify from 'vuetify';

// Vuex store
import store from '@/js/store/index';

// Router configuration
import router from '@/js/router/index';

// Root component file
import App from '@/js/views/App.vue';

Vue.use(Vuetify);

Vue.config.productionTip = false;

new Vue({
    vuetify: new Vuetify(),
    store,
    router,
    render: h => h(App)
}).$mount('#app');
