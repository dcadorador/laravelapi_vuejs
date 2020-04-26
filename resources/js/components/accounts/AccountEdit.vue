<template>
    <v-row>
        <v-col md="6">
            <v-card color="light" padding>
                <v-card-title class="headline">Edit Account</v-card-title>
                <account-form
                    v-if="account"
                    :clientName="account.client_name"
                    :clientNotes="account.client_notes"
                    :userId="account.user_id"
                    :submit="onAdd"
                    submitText="Update">
                </account-form>
                <v-progress-linear
                    v-else
                    indeterminate
                    color="primary"
                    class="mb-5"
                ></v-progress-linear>
            </v-card>
        </v-col>
        <v-col md="6" v-if="integrations">
            <h5 class="title">Integrations</h5>
            <p>Integration list of this account, click to view details.</p>
            <v-btn 
                v-for="integration in integrations"
                :key="'integration-' + integration.id"
                color="primary"
                class="m-1"
                :to='{ name: "integration edit", params: { id : integration.id } }'
            >
                {{ integration.label }}
            </v-btn>
        </v-col>
    </v-row>
</template>

<script>
import AccountForm from './AccountForm'
import { EventBus } from '../../event-bus.js'

export default {
    components: {
        AccountForm
    },
    data() {
        return {
            account: null,
            id: null,
            isSubmit: false,
            lastPage: null,
            integrations: null
        }
    },

    created() {
        this.id = this.$route.params.id
        this.lastPage = this.$route.query.page
        this.$http
            .get('/api/accounts/' + this.id)
            .then(({ data }) => {
                this.account = data.data.attributes
                this.integrations = this.account.integrations
                // console.log('account : ', self.account)
            })
            .catch(err => {
                EventBus.$emit('show-error', err);
            })
    },

    methods: {
        onAdd(data) {
            if (this.isSubmit) {
                return
            }

            this.isSubmit = true
            this.$http
                .put('/api/accounts/' + this.id, data)
                .then(res => {
                    // show info
                    EventBus.$emit('show-success', 'Successfully saved account.');
                    // console.log('res is : ', res)
                    let query = this.lastPage ? { page: this.lastPage } : {}
                    this.$router.replace({ name: 'account list', query: query });
                    this.isSubmit = false
                })
                .catch(err => {
                    EventBus.$emit('show-error', err);
                    this.isSubmit = false
                })
        }
    }
}
</script>