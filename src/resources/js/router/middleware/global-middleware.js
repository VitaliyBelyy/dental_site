import loadUser from './load-user';
import auth from './auth';
import guest from './guest';
import admin from './admin';
import unverified from './unverified';

export default (router) => {
    loadUser(router);
    auth(router);
    guest(router);
    admin(router);
    unverified(router);
}
