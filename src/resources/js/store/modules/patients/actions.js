import queryString from 'query-string';

const END_POINT = '/api/patients';

let actions = {
    loadPatients: ({ commit }, payload) => {
        const url = `${END_POINT}?${queryString.stringify(payload.params, {encode: false, arrayFormat: 'bracket'})}`;

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
};

export default actions;
