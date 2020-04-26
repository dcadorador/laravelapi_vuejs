<template>
    <div>
        <h5 class="title"><i class="fas fa-tachometer-alt"></i> Dashboard</h5>
        <v-container v-if="cards">
            <v-row>
                <v-col v-for="card in cards" :key="card.id">
                    <v-card
                        color="#385F73"
                        dark>
                        <v-card-title class="headline">
                            <i :class="card.icon" class="mr-3"></i>  {{ card.title }}
                        </v-card-title>

                        <v-card-subtitle>{{ card.desc }}</v-card-subtitle>

                        <v-card-actions>
                            <v-btn :to="card.link" text>Show Details</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>

            <v-divider></v-divider>
            <v-row dense>
                <v-col>
                    <v-btn small color="primary" :to="{ name: 'integration add' }">Add Integrations</v-btn>
                    <v-btn small color="primary" :to="{ name: 'account add' }">Add Account</v-btn>
                </v-col>
            </v-row>

            <v-row class="mt-3" dense>
                <v-col>
                    <h5>Latest Integration</h5>
                    <v-simple-table :light="true">
                        <template v-slot:default>
                            <thead>
                                <tr>
                                    <th class="text-left">Integration</th>
                                    <th class="text-left">Frequency (mins)</th>
                                    <th class="text-left">Last Run</th>
                                    <th class="text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody v-if="latestIntegrations.length > 0">
                                <tr 
                                    v-for="item in latestIntegrations" 
                                    :key="'item-' + item.id"
                                    @click="gotoIntegration(item.id)"
                                    class="clickable"
                                >
                                    <td>{{ item.label }}</td>
                                    <td>{{ item.frequency_mins }}</td>
                                    <td>{{ item.last_run || 'none' }}</td>
                                    <td>
                                        <v-chip x-small :color="getColor(item.integration_status)" dark>{{ item.integration_status }}</v-chip>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <p>No integration yet.</p>
                                    </td>
                                </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                </v-col>
            </v-row>
        </v-container>

        <v-container v-else>
            <v-row>
                <v-col>
                    <p>Please wait...</p>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
import { EventBus } from '../event-bus.js'

export default {
    data() {
        return {
            cards: [],
            latestIntegrations: [],
            isAdmin: true,
        }
    },

    mounted() {
        this.isAdmin = this.$store.getters.isAdmin
        this.init()
    },

    computed: {
        user() {
            return this.$store.state.user;
        }
    },

    methods: {
        init() {

            // if not admin we need to redirect to its integrations record sync lists
            if (!this.isAdmin) {
                this.$router.replace('/logs')
                return
            }

            this.$http
                .get('/api/dashboard')
                .then(({ data }) => {
                    // console.log('data : ', data)
                    this.cards = data.cards
                    this.latestIntegrations = data.integrations
                })
        },

        gotoIntegration(id) {
            this.$router.push({ name: 'integration edit', params: { id: id } });
        },
    }
}
</script>