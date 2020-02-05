import Vue from 'vue';
import VueRouter from 'vue-router';

// Routes
import paths from './paths'

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: paths,
});

export default router;

