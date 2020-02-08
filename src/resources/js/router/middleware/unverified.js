import store from '@/js/store/index';

export default function RedirectIfAuthenticated (router) {
    router.beforeEach((to, from, next) => {
        if (to.matched.some(record => record.meta.unverified)) {
            if (!store.getters['auth/isAuthorized'] || store.getters['auth/isVerified']) {
                return next(from.fullPath);
            }
        }

        return next();
    });
};
