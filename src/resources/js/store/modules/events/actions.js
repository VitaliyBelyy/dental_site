import queryString from "query-string";

const END_POINT = '/api/events';

let actions = {
    loadEvents: ({ commit }) => {
        let url = END_POINT;

        return new Promise((resolve, reject) => {
            window.httpClient.get(url)
                .then((response) => {
                    commit('setEvents', response.data.response);
                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject();
                });
        });
    },
    createEvent: ({ commit }, payload) => {
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
    updateEvent: ({ commit }, payload) => {
        let url = END_POINT + '/' + payload.id;

        return new Promise((resolve, reject) => {
            window.httpClient.put(url, payload.data)
                .then(response => {
                    resolve(response.data.response);
                })
                .catch(err => {
                    console.log(err.response);
                    reject();
                });
        });
    },
    deleteEvent: ({ commit }, id) => {
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
    loadPatients: ({ dispatch }, payload) => {
        let url = `/api/patients?${queryString.stringify(payload.params, {encode: false})}`;

        return new Promise((resolve, reject) => {
            window.httpClient.get(url)
                .then((response) => {
                    dispatch('setPatients', response.data.response);
                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject();
                });
        });
    },
    setPatients: ({ commit }, payload) => {
        commit('setPatients', payload);
    },
};

export default actions;
