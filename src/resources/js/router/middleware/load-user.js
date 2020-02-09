import store from '@/js/store/index';

export default function RedirectIfAuthenticated (router) {
    router.beforeEach(async (to, from, next) => {
        let token = store.state.auth.token;
        let user = store.state.auth.user;

        try {
            // if site have token but dont have user – need to get user
            token && !user && await store.dispatch('auth/userRequest');
        } catch (err) {
            if (err.response && err.response.status === 401) {
                await store.dispatch('auth/logout');

                return next({
                    name: 'auth.login',
                    params: { nextUrl: to.fullPath }
                });
            }
        }

        return next();
    });
}
