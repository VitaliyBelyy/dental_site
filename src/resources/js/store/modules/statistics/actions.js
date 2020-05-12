import queryString from "query-string";

const END_POINT = '/api/charts';

let actions = {
    loadChartData({ commit }, payload) {
        const url = `${END_POINT}/${payload.chartType}?${queryString.stringify(payload.params, {encode: false})}`;

        return new Promise((resolve, reject) => {
            window.httpClient.get(url)
                .then((response) => {
                    commit("setChartData", {
                        data: response.data.response.data,
                        chartType: payload.chartType,
                    });
                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject();
                });
        });
    },
    loadUsers: ({ commit }, payload) => {
        let url = `/api/users?${queryString.stringify(payload.params, {encode: false})}`;

        return new Promise((resolve, reject) => {
            window.httpClient.get(url)
                .then((response) => {
                    commit('setUsers', response.data.response);
                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject();
                });
        });
    },
    loadPatients: ({ commit }, payload) => {
        let url = `/api/patients?${queryString.stringify(payload.params, {encode: false})}`;

        return new Promise((resolve, reject) => {
            window.httpClient.get(url)
                .then((response) => {
                    commit('setPatients', response.data.response);
                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject();
                });
        });
    },
    loadRatio({ commit }, payload) {
        const url = `${END_POINT}/ratio?${queryString.stringify(payload.params, {encode: false})}`;

        return new Promise((resolve, reject) => {
            window.httpClient.get(url)
                .then((response) => {
                    commit("setRatio", response.data.response.data);
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
