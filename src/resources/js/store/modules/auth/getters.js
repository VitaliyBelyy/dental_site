let getters = {
    isAuthorized: state => state.user && state.user.full_name,
    isVerified: state => state.user && !!state.user.email_verified_at,
    isAdmin: state => state.user && state.user.roles && state.user.roles.length && state.user.roles.some(role => role === 'admin'),
};

export default getters;
