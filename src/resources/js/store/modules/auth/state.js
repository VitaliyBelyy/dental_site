import Cookies from 'js-cookie';

let state = {
    token: Cookies.get('access_token') || null,
    user: null
};

export default state;
