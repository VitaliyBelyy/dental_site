import Auth from '../layouts/Auth';
import Login from '../components/auth/Login';
import EmailVerification from '../components/auth/EmailVerification';
import EmailConfirmation from '../components/auth/EmailConfirmation';
import Register from '../components/auth/Register';
import Dashboard from '../layouts/Dashboard';
import unverified from "./middleware/unverified";

export default [
    {
        path: '/auth',
        component: Auth,
        children: [
            {
                path: '/',
                redirect: { name: 'auth.login' },
            },
            {
                path: 'login',
                name: 'auth.login',
                component: Login,
                meta: {
                    guest: true,
                },
            },
            {
                path: 'register',
                name: 'auth.register',
                component: Register,
                meta: {
                    guest: true,
                },
            },
            {
                path: 'email-verification',
                name: 'auth.email-verification',
                component: EmailVerification,
                meta: {
                    unverified: true,
                },
            },
            {
                path: 'email-confirmation',
                name: 'auth.email-confirmation',
                component: EmailConfirmation,
                meta: {
                    unverified: true,
                },
            },
        ]
    },
    {
        path: '/',
        name: 'dashboard', // TODO remove
        component: Dashboard,
        meta: {
            auth: true,
        },
    },
];



