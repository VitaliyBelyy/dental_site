let mutations = {
    setServices: (state, payload) => {
        state.services = payload;
    },
    setPagination: (state, payload) => {
        state.pagination = payload;
    }
};

export default mutations;
