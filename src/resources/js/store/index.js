import Vue from 'vue';
import Vuex from 'vuex';

import appModule from './modules/app';
import authModule from './modules/auth';
import usersModule from './modules/users';
import patientsModule from './modules/patients';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        app: appModule,
        auth: authModule,
        users: usersModule,
        patients: patientsModule,
    },
});
