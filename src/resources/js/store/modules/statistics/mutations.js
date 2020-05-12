let mutations = {
    setChartData(state, payload) {
        state.chartsData[payload.chartType] = payload.data;
    },
    setUsers: (state, payload) => {
        state.users = payload;
    },
    setPatients: (state, payload) => {
        state.patients = payload;
    },
    setRatio: (state, payload) => {
        state.ratio = payload;
    },
};

export default mutations;
