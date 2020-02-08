let mutations = {
    setToken: (state, token) => {
        state.token = token;
    },
    setUser: (state, user) => {
        state.user = user;
    },
    setVerificationStatus: (state, status) => {
        if (state.user) {
            state.user.emailVerificationStatus = status;
        }
    }
};

export default mutations;
