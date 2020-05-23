let actions = {
    setDrawerState: ({ commit }, value) => {
        commit('setDrawerState', value);
    },
    sendContactLetter: ({}, data) => {
        let url = '/api/contact-letter';

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
    }
};

export default actions;
