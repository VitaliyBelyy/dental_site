import queryString from 'query-string';

const END_POINT = '/api/services';

let actions = {
    loadServices: ({ commit }, payload) => {
        const url = `${END_POINT}?${queryString.stringify(payload.params, {encode: false})}`;

        return new Promise((resolve, reject) => {
            window.httpClient.get(url)
                .then((response) => {
                    commit('setServices', response.data.response);

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
    createService: ({ commit }, payload) => {
        let url = END_POINT;

        return new Promise((resolve, reject) => {
            window.httpClient.post(url, payload.data)
                .then(() => {
                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject();
                });
        });
    },
    updateService: ({ commit }, payload) => {
        let url = END_POINT + '/' + payload.id;

        return new Promise((resolve, reject) => {
            window.httpClient.put(url, payload.data)
                .then(() => {
                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject();
                });
        });
    },
    deleteService: ({ commit }, id) => {
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
