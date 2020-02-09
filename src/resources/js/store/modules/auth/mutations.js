let mutations = {
    setToken: (state, token) => {
        state.token = token;
    },
    setUser: (state, user) => {
        state.user = user;
    },
    setLoadingStatus: (state, status) => {
        state.isLoading = status;
    },
    setVerificationStatus: (state, status) => {
        if (state.user) {
            state.user.emailVerificationStatus = status;
        }
    },
    setValidationErrors(state, payload) {
        state.validationErrors = Object.assign({}, state.validationErrors, payload);
    },
    clearValidationErrors(state) {
        state.validationErrors = {};
    }
};

export default mutations;
