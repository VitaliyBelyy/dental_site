let mutations = {
    setUsers: (state, payload) => {
        state.users = payload;
    },
    setPagination: (state, payload) => {
        state.pagination = payload;
    },
    setSelectedUser: (state, payload) => {
        state.selectedUser = payload;
    },
};

export default mutations;
