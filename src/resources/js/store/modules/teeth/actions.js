const END_POINT = '/api/teeth';

let actions = {
    loadTeeth: ({ commit }) => {
        const url = END_POINT;

        return new Promise((resolve, reject) => {
            window.httpClient.get(url)
                .then((response) => {
                    commit('setTeethMap', response.data.response);
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
