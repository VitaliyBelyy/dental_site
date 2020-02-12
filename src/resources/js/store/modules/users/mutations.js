let mutations = {
    setUsers: (state, payload) => {
        state.users = payload;
    },
    setPagination: (state, payload) => {
        state.pagination = payload;
    },
    setDeletedDate: (state, payload) => {
        for (let user of state.users) {
            if (user.id === payload.id) {
                user.deleted_at = payload.date;
                break;
            }
        }
    }
};

export default mutations;
