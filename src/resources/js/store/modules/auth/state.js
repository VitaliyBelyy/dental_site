import Cookies from 'js-cookie';

let state = {
    token: Cookies.get('access_token') || null,
    user: null,
    isLoading: false,
    validationErrors: {},
};

export default state;
