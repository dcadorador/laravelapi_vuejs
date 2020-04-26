import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './store.js'
import Login from '@/js/components/Login';
const Dashboard = () => import('./components/Dashboard');
const Users = () => import('./components/users/Users');
const UserList = () => import('./components/users/UserList');
const UserAdd = () => import('./components/users/UserAdd');
const UserEdit = () => import('./components/users/UserEdit');
const Integrations = () => import('./components/integrations/Integrations');
const IntegrationsList = () => import('./components/integrations/IntegrationsList');
const IntegrationsAdd = () => import('./components/integrations/IntegrationsAdd');
const IntegrationsEdit = () => import('./components/integrations/IntegrationsEdit');
const IntegrationsMapping = () => import('./components/integrations/IntegrationsMapping');
const IntegrationsValueLookup = () => import('./components/integrations/IntegrationsValueLookup');
const Accounts = () => import('./components/accounts/Accounts');
const AccountList = () => import('./components/accounts/AccountList');
const AccountAdd = () => import('./components/accounts/AccountAdd');
const AccountEdit = () => import('./components/accounts/AccountEdit');
const Logs = () => import('./components/logs/Logs');
const LogSyncs = () => import('./components/logs/LogSyncs');
const RecordView = () => import('./components/logs/RecordView');





Vue.use(VueRouter);

const metaGuest = { guest: true, layout: 'no-sidebar-layout' }
const metaAuth  = { requiresAuth: true, layout: 'default-layout' }
const rolesSuperAdmin = ['Super Admin'];
const rolesAdmin = ['Super Admin', 'Admin'];

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/login',
            name: 'login',
            component: Login,
            meta: metaGuest
        },
        {
            path: '/',
            name: 'dashboard',
            component: Dashboard,
            meta: metaAuth
        },
        {
            path: '/users',
            name: 'users',
            component: Users,
            meta: Object.assign({...metaAuth}, {roles: rolesAdmin}),
            children: [
                {
                    path: '',
                    component: UserList,
                    name: 'user list'
                },
                {
                    path: 'add',
                    component: UserAdd,
                    name: 'user add'
                },
                {
                    path: ':id',
                    component: UserEdit,
                    name: 'user edit'
                }
            ]
        },
        {
            path: '/accounts',
            component: Accounts,
            name: 'accounts',
            meta: Object.assign({...metaAuth}, {roles: rolesSuperAdmin}),
            children: [
                {
                    path: '',
                    name: 'account list',
                    component: AccountList,
                },
                {
                    path: 'add',
                    name: 'account add',
                    component: AccountAdd,
                },
                {
                    path: ':id',
                    name: 'account edit',
                    component: AccountEdit,
                }
            ]
        },

        {
            path: '/integrations',
            component: Integrations,
            name: 'integrations',
            meta: Object.assign({...metaAuth}, {roles: rolesSuperAdmin}),
            children: [
                {
                    path: '',
                    name: 'integration list',
                    component: IntegrationsList,
                },
                {
                    path: 'add',
                    component: IntegrationsAdd,
                    name: 'integration add'
                },
                {
                    path: ':id',
                    component: IntegrationsEdit,
                    name: 'integration edit'
                },
                {
                    path: ':id/mapping',
                    component: IntegrationsMapping,
                    name: 'integration mapping'
                },
                // {
                //     path: ':id/valuelookups',
                //     component: IntegrationsValueLookup,
                //     name: 'integration valuelookup'
                // }
            ]
        },

        {
            path: '/logs',
            name: 'logs',
            component: Logs,
            children: [
                {
                    path: '',
                    component: LogSyncs,
                    name: 'log record sync'
                },
                {
                    path: ':id',
                    component: RecordView,
                    name: 'view Records'
                }
            ]
        },

        // {
        //     path: '/configuration',
        //     name: 'configuration',
        //     component: Configuration,
        //     meta: metaAuth
        // }
    ]
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (localStorage.getItem('jwt') == null) {
            next({
                path: '/login',
                params: { nextUrl: to.fullPath }
            })
        } else {
            if (store.state.user.id == null) {
                /*
                Watch for the user to be loaded. When it's finished, then
                we proceed.
                */
                store.watch( (state) => {
                    return state.user
                }, function(newUser) {
                    checkPermission(to, from, next, newUser);
                });
            } else {
                let user = store.state.user
                checkPermission(to, from, next, user)
            }
            
        }
    } else if(to.matched.some(record => record.meta.guest)) {
        if(localStorage.getItem('jwt') == null){
            next()
        } else{
            next({ name: 'dashboard'})
        }
    } else {
        next() 
    }
});


// check role position
const checkPermission = (to, from, next, user) => {
    if (to.matched.some(record => record.meta.roles)) {
        // now validate roles if this routes accept user role
        if (
            to.matched.some(
                record =>
                    record.meta.roles && 
                    record.meta.roles.indexOf(user.roles[0]) >= 0
            )
        ) {
            next()
        // otherwise, redirect back to dashboard
        } else {
            next({ name: 'dashboard'})
        }
    } else {
        next()
    }
}

export default router;