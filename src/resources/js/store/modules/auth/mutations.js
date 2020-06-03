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
    setVerificationDate: (state, date) => {
        if (state.user) {
            state.user.email_verified_at = date;
        }
    },
    updateUser(state, data) {
        state.user = Object.assign({}, state.user, data);
    }
};

export default mutations;
