import store from '@/js/store/index';

export default function RedirectIfAuthenticated (router) {
    router.beforeEach((to, from, next) => {
        if (to.matched.some(record => record.meta.auth)) {
            if (!store.getters['auth/isAuthorized']) {
                return next({
                    name: 'auth.login',
                    params: { nextUrl: to.fullPath }
                });
            } else if (!store.getters['auth/isVerified']) {
                return next({
                    name: 'auth.email-verification'
                });
            }
        }

        return next();
    });
};
