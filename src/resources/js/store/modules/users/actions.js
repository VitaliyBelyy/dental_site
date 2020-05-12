import queryString from 'query-string';

const END_POINT = '/api/users';

let actions = {
    loadUsers: ({ commit }, payload) => {
        const url = `${END_POINT}?${queryString.stringify(payload.params, {encode: false})}`;

        return new Promise((resolve, reject) => {
            window.httpClient.get(url)
                .then((response) => {
                    commit('setUsers', response.data.response);

                    if (response.data.meta.pagination) {
                        commit('setPagination', response.data.meta.pagination);
                    }

                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject();
                });
        });
    },
    createUser: ({ commit }, payload) => {
        let url = END_POINT;
        let data = Object.keys(payload.data).reduce((formData, key) => {
            if (payload.data[key] !== null) {
                formData.append(key, payload.data[key]);
            }
            return formData;
        }, new FormData());

        return new Promise((resolve, reject) => {
            window.httpClient.post(url, data, {
                headers: {'content-type': 'application/form-data'}
            })
                .then(() => {
                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject();
                });
        });
    },
    loadUser: ({ commit }, id) => {
        let url = END_POINT + '/' + id;

        return new Promise((resolve, reject) => {
            window.httpClient.get(url)
                .then(response => {
                    commit('setSelectedUser', response.data.response);
                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject();
                });
        });
    },
    updateUser: ({ commit }, payload) => {
        let url = END_POINT + '/' + payload.id;

        payload.data['_method'] = 'PUT';

        let data = Object.keys(payload.data).reduce((formData, key) => {
            if (payload.data[key] !== null) {
                formData.append(key, payload.data[key]);
            }
            return formData;
        }, new FormData());

        return new Promise((resolve, reject) => {
            window.httpClient.post(url, data, {
                headers: {'content-type': 'application/form-data'}
            })
                .then(response => {
                    resolve(response.data.response);
                })
                .catch(err => {
                    console.log(err.response);
                    reject();
                });
        });
    },
    deleteUser: ({ commit }, id) => {
        let url = END_POINT + '/' + id;

        return new Promise((resolve, reject) => {
            window.httpClient.delete(url)
                .then(() => {
                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject();
                });
        });
    },
};

export default actions;
