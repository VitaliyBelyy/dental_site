import Auth from '../layouts/Auth';
import Login from '../components/auth/Login';
import Register from '../components/auth/Register';
import EmailVerification from '../components/auth/EmailVerification';
import EmailConfirmation from '../components/auth/EmailConfirmation';
import ForgotPassword from "../components/auth/ForgotPassword";
import ResetPassword from "../components/auth/ResetPassword";
import Dashboard from '../layouts/Dashboard';

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
            {
                path: 'forgot-password',
                name: 'auth.forgot-password',
                component: ForgotPassword,
                meta: {
                    guest: true,
                },
            },
            {
                path: 'reset-password',
                name: 'auth.reset-password',
                component: ResetPassword,
                meta: {
                    guest: true,
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



