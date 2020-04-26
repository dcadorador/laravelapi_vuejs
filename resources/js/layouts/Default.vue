<template>
  <v-app id="fusedship">
    <v-navigation-drawer
        v-model="drawer"
        app
        dark
    >
        <v-list-item class="fusedship-logo-container">
            <v-list-item-content>
                <v-list-item-title class="fusedship-logo title d-flex">
                    <img src="/images/FShip_512x512.png" alt="">
                    <h3><span class="highlight">fused</span>ship</h3>
                </v-list-item-title>
            </v-list-item-content>
        </v-list-item>
        <v-list v-if="menus && menus.length > 0" dense nav class="list-unstyled components">
            <v-list-item
                v-for="menu in menus"
                :key="menu.title"
                link
                :to="menu.link"
                :href="menu.href"
            >
                <v-list-item-icon class="mt-4 mr-2">
                    <i :class="menu.icon"></i>
                </v-list-item-icon>

                <v-list-item-content>
                    <v-list-item-title>{{ menu.title }}</v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <!-- Signout Item -->
            <v-list-item link @click="onSignout">
                <v-list-item-icon class="mt-4 mr-2">
                    <!-- <i class="fas fa-sign-out-alt"></i> -->
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title>Signout</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </v-list>
        <div v-else>
            <v-skeleton-loader
                ref="skeleton"
                type="list-item"
                class="mx-auto"
            ></v-skeleton-loader>
            <v-skeleton-loader
                ref="skeleton"
                type="list-item"
                class="mx-auto"
            ></v-skeleton-loader>
            <v-skeleton-loader
                ref="skeleton"
                type="list-item"
                class="mx-auto"
            ></v-skeleton-loader>
            <v-skeleton-loader
                ref="skeleton"
                type="list-item"
                class="mx-auto"
            ></v-skeleton-loader>
        </div>
        
    </v-navigation-drawer>

    <v-app-bar
        app
        color="white"
        absolute
        class="fusedship-header"
        min-height="83"
    >
        <v-btn 
            small 
            class="mr-3"
            @click.stop="drawer = !drawer">
            <v-icon>mdi-dots-vertical</v-icon>
        </v-btn>
        <!-- <v-text-field
            hide-details
            dense
            append-icon="search"
            placeholder="Search"
            background-color="#f5f5f5"
            outlined
        ></v-text-field> -->
        <!-- <v-toolbar-title>Machship Sync</v-toolbar-title> -->
        <v-spacer></v-spacer>
        <!-- <span>{{ user ? 'Welcome ' + user.name + '!' : '' }}</span> -->
    </v-app-bar>

    <v-content>
      <v-container fluid>
        <v-row align="start">
          <v-col class="p-5" style="padding-top: 1.5rem !important;">
            <v-breadcrumbs :items="items" class="p-0 mb-3">
                <template v-slot:item="{ item }">
                    <v-breadcrumbs-item
                        :to="item.path"
                        :exact="true"
                        :disabled="item.disabled">
                        {{ item.name.toUpperCase() }}
                    </v-breadcrumbs-item>
                </template>
                <template v-slot:divider>
                    <i class="fa fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
            <div>
                <slot />
            </div>
          </v-col>
        </v-row>
      </v-container>
    </v-content>
    <v-snackbar
        v-model="info.isShow"
        :color="info.color"
        :multi-line="true"
        :right="true"
        :timeout="10000"
        :top="true"
    >
        {{ info.message }}
        <v-btn
            dark
            text
            @click="info.isShow = false">
            Close
        </v-btn>
    </v-snackbar>
    <v-overlay :value="overlay">
        {{ overlayMessage }}
    </v-overlay>
  </v-app>
</template>

<script>
import { EventBus } from '../event-bus.js'
import http from '../http.js'

export default {

    data() {
        return {
            home: { path: '/', name: 'dashboard', disabled: false },
            items: [],
            overlay: false,
            overlayMessage: '',

            // toast information
            info: {
                isShow: false,
                message: '',
                color: 'dark'
            },

            drawer: true,
            miniVariant: false
        }
    },

    created() {
        http.install()
        this.onInit()

        // Listen to the event.
        EventBus.$on('show-info', this.showInfo);
        EventBus.$on('show-error', this.showError);
        EventBus.$on('show-success', this.showSuccess);
    },

    beforeDestroy() {
        EventBus.$off('show-info', this.showInfo);
        EventBus.$off('show-error', this.showError);
        EventBus.$off('show-success', this.showSuccess);
    },

    watch:{
        $route (to, from){
            if (to.name != from.name) {
                this.checkBreadcrumbs()
            }
        }
    },

    computed: {
        path() {
            return this.$route.path
        },

        menus() {
            return this.$store.getters.getMenus
        },

        user() {
            return this.$store.state.user;
        }
    },

    methods: {
        // init
        onInit() {
            this.overlay = true
            this.overlayMessage = 'Initializing..'
            this.$http
                .post('api/checkauth')
                .then(({data}) => {
                    let user = data.data.attributes
                    user.id = data.data.id
                    this.overlay = false
                    this.$store.dispatch('setUser', user)
                })
                .catch(err => {
                    this.overlay = false
                    this.showError(err)
                })

            this.checkBreadcrumbs()
        },

        // on signing out
        onSignout() {
            this.overlay = true
            this.overlayMessage = 'Signing out...'
            setTimeout(function() {
                this.$http
                    .post('api/auth/logout')
                    .then(({data}) => {
                        localStorage.removeItem('jwt')
                        window.location.href = '/login'
                    })
                    .catch(err => {
                        this.overlay = false
                        this.showError(err)
                    })
            }.bind(this), 1000)
        },


        // TODO to improve routes breadcrumbs handling
        checkBreadcrumbs() {
            let routes = this.$route.matched;

            // reset and insert home default
            this.home.disabled = false
            this.items = []
            this.items.push(this.home);

            // iterate matched routes
            routes.forEach(route => {
                // validate routes if already existing
                if (route.name && route.name != '' && this.items.findIndex(item => item.name == route.name) == -1) {
                    this.items.push(route);
                }
            })

            this.items[this.items.length - 1].disabled = true
        },


        showSuccess(message) { this.showInfo(message, '#5d5'); },
        showError(message) {
            if (typeof message == 'object') {
                message = this.getErrors(message)
            }
            this.showInfo(message, '#d55');
        },
        showInfo(data, color = 'dark') {
            if (typeof data == 'object') {
                this.info.message = data.message
                this.info.color = data.color ? data.color : color
            } else {
                this.info.message = data
                this.info.color = color
            }

            this.info.isShow = true
        },
    }
};
</script>