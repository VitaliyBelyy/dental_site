let mutations = {
    setPatients: (state, payload) => {
        state.patients = payload;
    },
    setPagination: (state, payload) => {
        state.pagination = payload;
    },
    setAnamnesis: (state, payload) => {
        state.anamnesis = payload;
    },
    setSelectedPatient: (state, payload) => {
        state.selectedPatient = payload;
    },
    setVisitHistory: (state, payload) => {
        state.visitHistory = payload;
    },
    setVisitHistoryPagination: (state, payload) => {
        state.visitHistoryPagination = payload;
    },
    setVisitHistoryServices: (state, payload) => {
        state.visitHistoryServices = payload;
    },
    setServices: (state, payload) => {
        state.services = payload;
    },
    setPaymentHistory: (state, payload) => {
        state.paymentHistory = payload;
    },
    setPaymentHistoryPagination: (state, payload) => {
        state.paymentHistoryPagination = payload;
    },
};

export default mutations;
