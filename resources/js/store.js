import Vue from 'vue';
import Vuex from 'vuex'

Vue.use(Vuex);
const store = new Vuex.Store({
    state: {
        user: {
            id: null,
            name: null,
            roles: null,
            email: null
        },

        menus: null,
        allmenus: [
            { title: 'Dashboard', link: '/', icon: '' },
            { title: 'Users', link: '/users', icon: '' },
            { title: 'Accounts', link: '/accounts', icon: '' },
            { title: 'Integrations', link: '/integrations', icon: '' },
            { title: 'Logs', link: '/logs', icon: '' },
            { title: 'Queue', href: '/horizon/dashboard', icon: '' },
            // { title: 'Configuration', link: '/configuration', icon: 'fas fa-tasks' },
        ],

        // here we decide which menu will be display for each roles
        userRoles : {
            'Super Admin': -1, // all menu access
            'Admin': [0, 1, 4],
            'User': [4], // TODO logs page
        },
    },
    mutations: {
        FETCH_USER(state, user) {
            state.user = user
        },
    },

    actions: {
        setUser({ commit, state }, data) {
            // state.user = data
            commit("FETCH_USER", data);
        }
    },

    getters: {
        getUser(state) {
            return state.user
        },
        getMenus(state) {

            let menus = []
            let userMenus = []
            let roles = state.user.roles
            let allmenus = state.allmenus
            let userRoles = state.userRoles

            if (roles) {
                // now we need to determine which menu index does this user will have
                roles.forEach(role => {
                    if (userRoles[role]) {
                        if (Array.isArray(userRoles[role])) {
                            userMenus = userMenus.concat(userRoles[role]);
                        } else {
                            userMenus.push(state.userRoles[role])
                        }
                    }
                })


                allmenus.forEach((menu, index) => {
                    if (userMenus.indexOf(-1) >= 0 || userMenus.indexOf(index) >= 0) {
                        menus.push(menu)
                    }
                })
            }

            return menus
        },
        // check whether this is an admin or not
        isAdmin(state) {
            return state.user.roles[0] === 'User' ? false : true
        }
    }
})

export default store;