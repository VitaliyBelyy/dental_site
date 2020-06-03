import Cookies from 'js-cookie';
import moment from 'moment';

const END_POINT = '/api/auth';
const ONE_HOUR = 1/24;
const ONE_YEAR = 365;

let actions = {
    login: ({ commit }, payload) => {
        let url = END_POINT + '/login';

        return new Promise((resolve, reject) => {
            window.httpClient.post(url, payload.data)
                .then(response => {
                    let user = response.data.response.user;
                    let token = user.access_token;

                    window.httpClient.bindToken(token);

                    // Check that remember me checkbox is active
                    let expires = payload.remember ? ONE_YEAR : ONE_HOUR;

                    Cookies.set('access_token', token, { expires });

                    commit('setToken', token);
                    commit('setUser', user);
                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject(err.response);
                });
        });
    },
    // register: ({ commit }, payload) => {
    //     let url = END_POINT + '/register';

    //     return new Promise((resolve, reject) => {
    //         window.httpClient.post(url, payload.data)
    //             .then(response => {
    //                 let user = response.data.response.user;
    //                 let token = user.access_token;

    //                 window.httpClient.bindToken(token);

    //                 Cookies.set('access_token', token, { expires: ONE_HOUR });

    //                 commit('setToken', token);
    //                 commit('setUser', user);
    //                 resolve();
    //             })
    //             .catch(err => {
    //                 console.log(err.response);
    //                 reject(err.response);
    //             });
    //     });
    // },
    userRequest: ({ commit, dispatch }) => {
        commit('setLoadingStatus', true);

        return new Promise((resolve, reject) => {
            dispatch('loadUser')
                .then(() => {
                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject(err.response);
                })
                .finally(() => {
                    commit('setLoadingStatus', false);
                });
        });
    },
    loadUser: ({ commit }) => {
        let url = END_POINT + '/user';

        return new Promise((resolve, reject) => {
            window.httpClient.get(url)
                .then(response => {
                    let user = response.data.response.user;

                    commit('setUser', user);
                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject(err.response);
                });
        });
    },
    logout: ({ commit }) => {
        return new Promise((resolve) => {
            window.httpClient.removeToken();

            Cookies.remove('access_token');

            commit('setToken', null);
            commit('setUser', null);
            resolve();
        });
    },
    resend: ({ commit }) => {
        let url = END_POINT + '/email/resend';

        return new Promise((resolve, reject) => {
            window.httpClient.post(url)
                .then(() => {
                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject(err.response);
                });
        });
    },
    verify: ({ commit }, queryURL) => {
        return new Promise((resolve, reject) => {
            window.httpClient.post(queryURL)
                .then(() => {
                    commit('setVerificationDate', moment().format('YYYY-MM-DD'));
                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject(err.response);
                });
        });
    },
    sendResetLink: ({ commit }, data) => {
        let url = END_POINT + '/password/email';

        return new Promise((resolve, reject) => {
            window.httpClient.post(url, data)
                .then(() => {
                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject(err.response);
                });
        });
    },
    resetPassword: ({ commit }, data) => {
        let url = END_POINT + '/password/reset';

        return new Promise((resolve, reject) => {
            window.httpClient.post(url, data)
                .then(() => {
                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject(err.response);
                });
        });
    },
    updateUser: ({ commit }, data) => {
        commit('updateUser', data);
    },
};

export default actions;
