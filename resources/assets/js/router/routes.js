import ProvidersMain from "./../components/providers/Main.vue";
import ProvidersList from "./../components/providers/List.vue";
import NewProvider from "./../components/providers/New.vue";
import Provider from "./../components/providers/View.vue";
import Home from "./../components/Home.vue";
import Login from "./../components/auth/Login.vue";

export const routes = [
    {
        path: '/',
        component: Home,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/login',
        component: Login
    },
    {
        path: '/providers',
        component: ProvidersMain,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: '/',
                component: ProvidersList
            },
            {
                path: 'new',
                component: NewProvider
            },
            {
                path: ':id',
                component: Provider
            }
        ]
    }
];