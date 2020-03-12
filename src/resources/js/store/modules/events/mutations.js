let mutations = {
    setEvents: (state, payload) => {
        state.events = payload;
    },
    setPatients: (state, payload) => {
        state.patients = payload;
    },
};

export default mutations;
