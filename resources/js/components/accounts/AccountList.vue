<template>
    <div>
        <v-card class="mb-5">
            <v-card-title>
                <i class="fas fa-users mr-3"></i> Account Lists
                <v-spacer></v-spacer>
                <v-text-field
                    append-icon="search"
                    label="Search"
                    single-line
                    hide-details
                    style="margin-top: -10px;"
                    @change="onSearch($event)">
                </v-text-field>
            </v-card-title>
            <v-data-table
                v-if="items"
                :headers="headers"
                :options.sync="options"
                :server-items-length="total"
                :items="items"
                :loading="loading"
                :disable-sort="true">
                <!-- action to view records -->
                <template v-slot:item.actions="{ item }">
                    <v-btn small color="success" :to="'/accounts/' + item.id">Edit</v-btn>
                    <v-btn small color="error" @click="onDelete(item)">Delete</v-btn>
                </template>
            </v-data-table>

            <v-progress-linear
                v-else
                indeterminate
                color="primary"
                class="mb-5"
            ></v-progress-linear>
        </v-card>

        <Modal
            ref="modal"
            color="error"
            okText="delete" />
    </div>
</template>

<script>
import { EventBus } from '../../event-bus.js'
import Modal from '../Modal'

export default {

    components: {
        Modal
    },

    data() {
        return {
            items: null,
            page: 0,
            length: 0,
            size: 0,
            accountId: 0,

            loading: true,
            search: '',
            headers: [
                { text: 'Client Name', value: 'client_name' },
                { text: 'Client Notes', value: 'client_notes' },
                { text: 'User', value: 'user' },
                { text: 'Total Integrations', value: 'integrations' },
                { text: 'Actions', value: 'actions', width: 250, sortable: false, },
            ],
            options: this.getPaginateOptions(),
        }
    },

    mounted() {
        this.fetchPage()
    },

    computed: {
        params(nv) {
            return {
                ...this.options,
                query: this.search
            };
        }
    },

    watch: {
        params(val, oldVal) {
            // this is to avoid redundant request
            if (JSON.stringify(val) != JSON.stringify(oldVal)) {
                this.fetchPage()
            }
        },
    },

    methods: {
        onDelete(item) {
            this.accountId = item.id
            this.$refs.modal.onShow(
                'Delete Confirmation',
                'Are you sure you want to delete this account?',
                this.onDeleteConfirm
            );
        },

        onDeleteConfirm() {

            this.$http
                .delete('/api/accounts/' + this.accountId)
                .then(({ data }) => {
                    EventBus.$emit('show-success', 'Successfully deleted an account.');

                    // remove current item selected
                    let index = this.items.findIndex(item => item.id == this.accountId)
                    if (index >= 0) {
                        this.items.splice(index, 1);
                    }
                })
                .catch(err => {
                    EventBus.$emit('show-error', err);
                })
        },

        onSearch(text) {
            this.search = text
            this.options.page = 1
        },

        fetchPage() {
            const { sortBy, sortDesc, page, itemsPerPage } = this.options
            this.loading = true

            this.$http
                .get('/api/accounts', {
                    params: {
                        page: page,
                        limit: itemsPerPage,
                        sortBy: sortBy ? sortBy[0] : null,
                        sortDesc: sortDesc ? sortDesc[0] : null,
                        search: this.search
                    }
                })
                .then(({data}) => {
                    // map data to items
                    this.items = data.data.map(item => {
                        return {
                            id: item.id,
                            client_name: item.attributes.client_name,
                            client_notes: item.attributes.client_notes,
                            user: item.attributes.user ? item.attributes.user.name : '',
                            integrations: item.attributes.integration_total
                        }
                    })

                    // pagination
                    let pagination = data.meta.pagination
                    this.page      = pagination.current_page
                    this.length    = pagination.total_pages
                    this.size      = pagination.per_page
                    this.total     = pagination.total

                    this.loading = false
                })
                .catch(err => {
                    EventBus.$emit('show-error', err);
                    this.loading = false
                })
        }
    }
}
</script>