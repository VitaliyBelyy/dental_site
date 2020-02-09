import Cookies from 'js-cookie';

const END_POINT = '/api/auth';
const ONE_HOUR = 1/24;
const ONE_YEAR = 365;

let actions = {
    login: ({ commit }, payload) => {
        let url = END_POINT + '/login';

        return new Promise((resolve, reject) => {
            window.httpClient.post(url, payload.data)
                .then(response => {
                    let data = response.data.response;
                    let token = data.access_token;
                    let user = {
                        fullname: data.fullname || null,
                        email: data.email || null,
                        emailVerificationStatus: !!data.email_verified_at,
                        roles: data.roles || [],
                    };

                    window.httpClient.bindToken(token);

                    // Check that remember me checkbox is active
                    let expires = payload.remember ? ONE_YEAR : ONE_HOUR;

                    Cookies.set('access_token', token, { expires });

                    commit('setToken', token);
                    commit('setUser', user);
                    resolve();
                })
                .catch(err => {
                    if (err.response.status === 422) {
                        const errors = err.response.data.meta.errors;
                        errors && commit("setValidationErrors", errors);
                    }

                    console.log(err.response);
                    reject();
                });
        });
    },
    register: ({ commit }, payload) => {
        let url = END_POINT + '/register';

        return new Promise((resolve, reject) => {
            window.httpClient.post(url, payload.data)
                .then(response => {
                    let data = response.data.response;
                    let token = data.access_token;
                    let user = {
                        fullname: data.fullname || null,
                        email: data.email || null,
                        emailVerificationStatus: !!data.email_verified_at,
                        roles: data.roles || [],
                    };

                    window.httpClient.bindToken(token);

                    Cookies.set('access_token', token, { expires: ONE_HOUR });

                    commit('setToken', token);
                    commit('setUser', user);
                    resolve();
                })
                .catch(err => {
                    if (err.response.status === 422) {
                        const errors = err.response.data.meta.errors;
                        errors && commit("setValidationErrors", errors);
                    }

                    console.log(err.response);
                    reject();
                });
        });
    },
    userRequest: ({ commit, dispatch }) => {
        commit('setLoadingStatus', true);

        return new Promise((resolve, reject) => {
            dispatch('loadUser')
                .then(() => {
                    resolve();
                })
                .catch(err => {
                    reject(err);
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
                    let data = response.data.response;
                    let user = {
                        fullname: data.fullname || null,
                        email: data.email || null,
                        emailVerificationStatus: !!data.email_verified_at,
                        roles: data.roles || [],
                    };

                    commit('setUser', user);
                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject(err);
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
                    reject();
                });
        });
    },
    verify: ({ commit }, queryURL) => {
        return new Promise((resolve, reject) => {
            window.httpClient.post(queryURL)
                .then(() => {
                    commit('setVerificationStatus', true);
                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject();
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
                    reject();
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
                    reject();
                });
        });
    },
    clearValidationErrors: ({ commit }) => {
        commit('clearValidationErrors');
    }
};

export default actions;
