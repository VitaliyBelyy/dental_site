import Vue from 'vue';
import VueRouter from 'vue-router';

// Routes
import routes from './routes';

import globalMiddleware from './middleware/global-middleware';

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes,
});

globalMiddleware(router);

export default router;

