import queryString from 'query-string';

const END_POINT = '/api/patients';

let actions = {
    loadPatients: ({ commit }, payload) => {
        let url = `${END_POINT}?${queryString.stringify(payload.params, {encode: false})}`;

        return new Promise((resolve, reject) => {
            window.httpClient.get(url)
                .then((response) => {
                    commit('setPatients', response.data.response);

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
    loadAnamnesis: ({ commit }) => {
        let url = '/api/anamnesis';

        return new Promise((resolve, reject) => {
            window.httpClient.get(url)
                .then((response) => {
                    commit('setAnamnesis', response.data.response);
                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject();
                });
        });
    },
    createPatient: ({ commit }, payload) => {
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
    loadPatient: ({ commit }, id) => {
        let url = END_POINT + '/' + id;

        return new Promise((resolve, reject) => {
            window.httpClient.get(url)
                .then(response => {
                    commit('setSelectedPatient', response.data.response);
                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject();
                });
        });
    },
    updatePatient: ({ commit }, payload) => {
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
                .then(() => {
                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject();
                });
        });
    },
    loadServiceHistory: ({ commit }, payload) => {
        let url = `${END_POINT}/${payload.id}/service-history?${queryString.stringify(payload.params, {encode: false})}`;

        return new Promise((resolve, reject) => {
            window.httpClient.get(url)
                .then((response) => {
                    commit('setServiceHistory', response.data.response);

                    if (response.data.meta.pagination) {
                        commit('setServiceHistoryPagination', response.data.meta.pagination);
                    }

                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject();
                });
        });
    },
    loadServices: ({ commit }, payload) => {
        let url = `/api/services?${queryString.stringify(payload.params, {encode: false})}`;

        return new Promise((resolve, reject) => {
            window.httpClient.get(url)
                .then((response) => {
                    commit('setServices', response.data.response);
                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject();
                });
        });
    },
    createServiceHistoryRecord: ({ commit }, payload) => {
        let url = END_POINT + '/' + payload.id + '/service-history';

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
    loadPaymentHistory: ({ commit }, payload) => {
        let url = `${END_POINT}/${payload.id}/payment-history?${queryString.stringify(payload.params, {encode: false})}`;

        return new Promise((resolve, reject) => {
            window.httpClient.get(url)
                .then((response) => {
                    commit('setPaymentHistory', response.data.response);

                    if (response.data.meta.pagination) {
                        commit('setPaymentHistoryPagination', response.data.meta.pagination);
                    }

                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject();
                });
        });
    },
    createPaymentHistoryRecord: ({ commit }, payload) => {
        let url = END_POINT + '/' + payload.id + '/payment-history';

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
};

export default actions;
