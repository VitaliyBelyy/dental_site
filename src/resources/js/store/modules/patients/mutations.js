let mutations = {
    setPatients: (state, payload) => {
        state.patients = payload;
    },
    setPagination: (state, payload) => {
        state.pagination = payload;
    },
};

export default mutations;
