<template>

    <v-container fluid>
        <v-row dense>
            <!-- edit integration form -->
            <v-col>
                <v-card 
                    color="light"
                    padding
                    elevation="6">
                    <v-card-title class="headline">
                        Edit Integration
                        <v-spacer></v-spacer>
                        <v-switch v-model="isActivate" color="primary"></v-switch><span class="subtitle-2">Activate</span>
                    </v-card-title>
                    <integrations-form
                        v-if="integration"
                        :integration="integration"
                        :integration_id="integrationId"
                        :integration_type_id="integration.integration_type_id"
                        :submit="onUpdate"
                        submitText="Update">
                    </integrations-form>
                    <v-progress-linear
                        v-else
                        indeterminate
                        color="primary"
                        class="mb-5"
                    ></v-progress-linear>


                    <div class="p-3" v-if="integration">
                        <v-tabs
                            class="fusedship-tab"
                            background-color="white"
                            v-model="tab"
                            color="dark">
                            <v-tabs-slider></v-tabs-slider>
                            <v-tab href="#to-machship">
                                TO Machship
                            </v-tab>
                            <v-tab href="#from-machship">
                                FROM Machship
                            </v-tab>
                            <v-tab-item value="to-machship">
                                <IntegrationsValueLookupsForm :id="integrationId" :direction="'to_machship'" />
                            </v-tab-item>

                            <v-tab-item value="from-machship">
                                <IntegrationsValueLookupsForm :id="integrationId" :direction="'from_machship'" />
                            </v-tab-item>

                        </v-tabs>
                    </div>

                </v-card>
            </v-col>
        </v-row>

    </v-container>
</template>

<script>
import IntegrationsForm from './IntegrationsForm.vue'
import IntegrationsValueLookupsForm from './IntegrationsValueLookupsForm'
import { EventBus } from '../../event-bus.js'

export default {

    components: {
        IntegrationsForm,
        IntegrationsValueLookupsForm,
    },

    data() {
        return {
            tab: 'tab-mapping',
            integrationId: null,
            integration: null,
            isSubmit: false,
            isActivate: null
        }
    },

    mounted() {
        this.integrationId = parseInt(this.$route.params.id)
        this.$http
            .get('/api/integrations/' + this.integrationId)
            .then(({ data }) => {
                this.integration = data.data.attributes
                this.isActivate = this.integration.integration_status == 'active' ? true : false
            })
            .catch(err => {
                EventBus.$emit('show-error', err);
            })
    },

    watch: {
        isActivate: function(value, oldValue) {
            if (oldValue != null) {
                this.onSetActivation(value)
                console.log('is activate : ', value)
            }
        }
    },

    methods: {
        onUpdate(data) {
            if (this.isSubmit) {
                return
            }

            this.isSubmit = true
            this.$http
                .put('/api/integrations/' + this.integrationId, data)
                .then(res => {
                    // show info
                    EventBus.$emit('show-success', 'Successfully saved integrations.');
                    this.isSubmit = false
                })
                .catch(err => {
                    EventBus.$emit('show-error', err);
                    this.isSubmit = false
                })
        },

        onSetActivation(value) {
            let status = value ? 'active' : 'inactive'
            this.$http
                .post('/api/integrations/activate', {
                    integration_id: this.integrationId,
                    integration_status: status
                })
                .then(() => {
                    EventBus.$emit('show-success', 'Successfully sets integration status to ' + status)
                })
                .catch(err => {
                    EventBus.$emit('show-error', err)
                })
        },
    },
}
</script>