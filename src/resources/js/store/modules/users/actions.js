import queryString from 'query-string';

const END_POINT = '/api/users';

let actions = {
    loadUsers: ({ commit }, payload) => {
        const url = `${END_POINT}?${queryString.stringify(payload.params, {encode: false, arrayFormat: 'bracket'})}`;

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
    deleteUser: ({ commit }, id) => {
        let url = END_POINT + '/' + id;

        return new Promise((resolve, reject) => {
            window.httpClient.delete(url)
                .then(response => {
                    const date = response.data.response.deleted_at;

                    commit('setDeletedDate', {id, date});
                    resolve();
                })
                .catch(err => {
                    console.log(err.response);
                    reject();
                });
        });
    },
    restoreUser: ({ commit }, id) => {
        let url = END_POINT + '/' + id + '/restore';

        return new Promise((resolve, reject) => {
            window.httpClient.post(url)
                .then(() => {
                    commit('setDeletedDate', {id, date: null});
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
