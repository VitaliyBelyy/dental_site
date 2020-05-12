import Auth from '../layouts/Auth';
import Login from '../components/auth/Login';
import Register from '../components/auth/Register';
import EmailVerification from '../components/auth/EmailVerification';
import EmailConfirmation from '../components/auth/EmailConfirmation';
import ForgotPassword from "../components/auth/ForgotPassword";
import ResetPassword from "../components/auth/ResetPassword";
import Dashboard from '../layouts/Dashboard';
import Statistics from "../components/dashboard/Statistics";
import Users from "../components/dashboard/Users";
import CreateUser from "../components/dashboard/CreateUser";
import EditUser from "../components/dashboard/EditUser";
import Patients from "../components/dashboard/Patients";
import CreatePatient from "../components/dashboard/CreatePatient";
import PatientProfile from "../components/dashboard/PatientProfile";
import EditPatient from "../components/dashboard/EditPatient";
import VisitHistory from "../components/dashboard/VisitHistory";
import PaymentHistory from "../components/dashboard/PaymentHistory";
import Calendar from "../components/dashboard/Calendar";
import Services from "../components/dashboard/Services";

import store from '@/js/store/index';

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
        component: Dashboard,
        meta: {
            auth: true,
        },
        children: [
            {
                path: '/',
                redirect: { name: 'dashboard.patients' },
            },
            {
                path: 'statistics',
                name: 'dashboard.statistics',
                component: Statistics,
                meta: { title: "Statistics", admin: true },
            },
            {
                path: 'users',
                name: 'dashboard.users',
                component: Users,
                meta: { title: "Users", admin: true },
            },
            {
                path: 'users/create',
                name: 'dashboard.create-user',
                component: CreateUser,
                meta: { admin: true },
            },
            {
                path: 'users/:id/edit',
                name: 'dashboard.edit-user',
                component: EditUser,
                beforeEnter: (to, from, next) => {
                    if (store.getters['auth/isAdmin'] || (store.state.auth.user && store.state.auth.user.id === to.params.id)) {
                        return next();
                    }
                    
                    return next(from.fullPath);
                },
                props: true,
            },
            {
                path: 'patients',
                name: 'dashboard.patients',
                component: Patients,
                meta: { title: "Patients" },
            },
            {
                path: 'patients/create',
                name: 'dashboard.create-patient',
                component: CreatePatient,
            },
            {
                path: 'patients/:id',
                name: 'dashboard.patient-profile',
                component: PatientProfile,
                props: true
            },
            {
                path: 'patients/:id/edit',
                name: 'dashboard.edit-patient',
                component: EditPatient,
                props: true
            },
            {
                path: 'patients/:id/visit-history',
                name: 'dashboard.visit-history',
                component: VisitHistory,
                props: true
            },
            {
                path: 'patients/:id/payment-history',
                name: 'dashboard.payment-history',
                component: PaymentHistory,
                props: true
            },
            {
                path: 'calendar',
                name: 'dashboard.calendar',
                component: Calendar,
            },
            {
                path: 'services',
                name: 'dashboard.services',
                component: Services,
                meta: { title: "Services", admin: true },
            },
        ]
    },
];



