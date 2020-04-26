<template>
    <v-form
        ref="form"
        v-model="valid"
        @submit="onSubmit">

        <v-container fluid>
            <v-row>
                <v-col>
                    <!-- integration label -->
                    <v-text-field
                        v-model="form.label"
                        :error-messages="labelErrors"
                        :counter="30"
                        label="Integration label"
                        required
                        @input="$v.form.label.$touch()"
                        @blur="$v.form.label.$touch()">
                    </v-text-field>
                </v-col>
                <v-col>
                    <!-- Platform -->
                    <v-select
                        v-model="form.integration_type_id"
                        :items="integrations"
                        :error-messages="typeErrors"
                        :rules="[v => !!v || 'Platform is required']"
                        label="Platform"
                        required>
                    </v-select>
                </v-col>
            </v-row>
            <v-row v-if="submitText !== 'Update'">
                <v-col md="6">
                    <!-- integration account -->
                    <v-select
                        v-model="form.account_id"
                        :items="accounts"
                        :error-messages="typeErrors"
                        :rules="[v => !!v || 'Account is required']"
                        label="Account"
                        required>
                    </v-select>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-tabs
                        class="fusedship-tab"
                        background-color="white"
                        color="dark">
                        <v-tab href="#tab-settings">
                            Settings
                        </v-tab>
                        <v-tab v-if="submitText === 'Update'" href="#tab-machship">
                            Machship
                        </v-tab>
                        <v-tab v-if="submitText === 'Update' && platform !== ''" href="#tab-platform">
                            {{ platform }}
                        </v-tab>
                        <v-tab v-if="submitText === 'Update'" href="#tab-filters">
                            Filters
                        </v-tab>

                        <v-tab-item value="tab-settings">
                            <v-container fluid>
                                <v-row>
                                    <v-col>
                                        <!-- frequency -->
                                        <v-text-field
                                            v-model="form.frequency_mins"
                                            :error-messages="frequencyErrors"
                                            label="Frequency by mins"
                                            @input="$v.form.frequency_mins.$touch()"
                                            @blur="$v.form.frequency_mins.$touch()">
                                        </v-text-field>
                                    </v-col>
                                    <v-col>
                                        <label for="">Is Pending Consignment</label>
                                        <v-switch v-model="form.is_pending" class="ma-2"></v-switch>
                                    </v-col>
                                </v-row>

                                <v-row>
                                    <v-col md="6">
                                        <!-- offset -->
                                        <v-text-field
                                            v-model="form.offset"
                                            :error-messages="offsetErrors"
                                            label="Offset sync by mins"
                                            @input="$v.form.offset.$touch()"
                                            @blur="$v.form.offset.$touch()">
                                        </v-text-field>
                                    </v-col>
                                </v-row>

                                <v-row>
                                    <v-col>
                                        <div class="mt-3 d-flex">
                                            <v-spacer></v-spacer>
                                            <v-btn type="submit" small class="mr-1" color="primary">{{ submitText }}</v-btn>
                                        </div>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-tab-item>

                        <v-tab-item value="tab-machship">
                            <!-- machship setup -->
                            <integrations-machship
                                v-if="integration"
                                :integrationId="integration.id"
                                :integrationKeys="integration.keys">
                            </integrations-machship>
                        </v-tab-item>

                        <v-tab-item value="tab-platform">
                            <integrations-meta
                                v-if="integration"
                                :integrationId="integration.id"
                                :integrationMeta="integration.metas">
                            </integrations-meta>
                        </v-tab-item>

                        <v-tab-item value="tab-filters">
                            <!-- filter data source -->
                            <integrations-filter
                                v-if="integration"
                                :integrationId="integration.id"
                                :integrationFilters="integration.filters">
                            </integrations-filter>
                        </v-tab-item>
                    </v-tabs>
                </v-col>
            </v-row>
        </v-container>
    </v-form>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required, maxLength, numeric } from 'vuelidate/lib/validators'
import { EventBus } from '../../event-bus.js'
import IntegrationsMachship from './IntegrationsMachship.vue'
import IntegrationsMeta from './IntegrationsMeta.vue'
import IntegrationsFilter from './IntegrationsFilter.vue'

export default {
    components: {
        IntegrationsMachship,
        IntegrationsMeta,
        IntegrationsFilter
    },
    mixins: [validationMixin],
    validations: {
        form : {
            label: { required, maxLength: maxLength(30) },
            integration_type_id: { required },
            frequency_mins: { required, numeric },
            offset: { required, numeric }
        }
    },
    props: {
        integration:      { type: Object, default: {} },
        submitText:       { type: String, default: 'Add' },
        integration_type_id: { type: Number, default: null },
        submit:           { type: Function, default: () => {} },
        integration_id:   { type: Number, default: null }
    },
    data() {
        return {
            valid: false,
            integrations: [],
            accounts: [],

            // form inputs
            form: {
                label: this.integration.label,
                integration_type_id: this.integration_type_id,
                frequency_mins: this.integration.frequency_mins,
                account_id: this.integration.account_id,
                is_pending: this.integration.master_consignment_type == 'PENDING',
                offset: this.integration.offset
            }
        }
    },

    created() {
        // we need to fetch all accounts
        this.onFetchAllOptions()
    },

    computed: {
        // TODO
        labelErrors() {
            const errors = []
            if (!this.$v.form.label.$dirty) return errors
            !this.$v.form.label.maxLength && errors.push('Label must be at most 10 characters long')
            !this.$v.form.label.required && errors.push('Label is required.')
            return errors
        },
        typeErrors() {
            const errors = []
            if (!this.$v.form.integration_type_id.$dirty) return errors
            !this.$v.form.integration_type_id.required && errors.push('Integration type is required.')
            return errors
        },
        frequencyErrors() {
            const errors = []
            if (!this.$v.form.frequency_mins.$dirty) return errors
            !this.$v.form.frequency_mins.numeric && errors.push('Frequency must be numeric')
            !this.$v.form.frequency_mins.required && errors.push('Frequency is required.')
            return errors
        },
        offsetErrors() {
            const errors = []
            if (!this.$v.form.offset.$dirty) return errors
            !this.$v.form.offset.numeric && errors.push('Frequency must be numeric')
            !this.$v.form.offset.required && errors.push('Frequency is required.')
            return errors
        },

        platform() {
            let id = this.integration_type_id
            let index = this.integrations.findIndex(integration => {
                return integration.value == id
            })

            return index < 0 ? '' : this.integrations[index].text
        }
    },

    methods: {
        onSubmit(e) {
            this.$v.form.$touch();
            e.preventDefault();

            if (this.valid) {
                // update integration's consignment type
                this.form.master_consignment_type = this.form.is_pending ? 'PENDING' : 'MANIFEST'
                // call callback submit
                this.submit(this.form)
            } else {
                EventBus.$emit('show-info', 'Failed! Please try again.');
            }
        },
        clear() {
            this.$refs.form.reset()
        },

        onFetchAllOptions() {
            this.$http
                .get('/api/accounts')
                .then(({ data }) => {
                    console.log(data.data)
                    this.accounts = data.data.map(account => {
                        return { value: parseInt(account.id), text: account.attributes.client_name }
                    })
                })
                .catch(err => {
                    EventBus.$emit('show-error', 'Failed to fetch users.');
                })

            this.$http
                .get('/api/integrationtypes')
                .then(({ data }) => {
                    this.integrations = data.data.map(integration => {
                        return { value: parseInt(integration.id), text: integration.attributes.display_name }
                    })
                })
                .catch(err => {
                    EventBus.$emit('show-error', 'Failed to fetch integration types.');
                })
        }
    },
}
</script>
