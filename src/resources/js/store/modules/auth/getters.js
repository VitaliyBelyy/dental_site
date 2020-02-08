let getters = {
    isAuthorized: state => state.user && state.user.fullname,
    isVerified: state => state.user && state.user.emailVerificationStatus,
    isAdmin: state => state.user && state.user.roles && state.user.roles.length && state.user.roles.some(role => role === 'admin'),
};

export default getters;
