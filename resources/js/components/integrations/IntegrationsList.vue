<template>
    <div>
        <v-card>
            <v-card-title>
                <i class="fas fa-list mr-3"></i> Integration Lists
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
                    <v-btn small color="success" :to="'/integrations/' + item.id">Edit</v-btn>
                    <!-- <v-btn small color="gray" :to="'/integrations/' + item.id + '/valuelookups'">Value Lookups</v-btn> -->
                    <v-btn small color="error" @click="onDelete(item)">Delete</v-btn>
                </template>

                <!-- styling sync status -->
                <template v-slot:item.status="{ item }">
                  <v-chip x-small :color="getColor(item.status)" dark>{{ item.status }}</v-chip>
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
import Modal from '../Modal'
import { EventBus } from '../../event-bus.js'

export default {
    components: {
        Modal
    },

    data() {
        return {
            headers: [
                { text: 'Integration label', value: 'label' },
                { text: 'Integration type', value: 'integration_type' },
                { text: 'Account', value: 'account' },
                { text: 'Frequency (mins)', value: 'frequency' },
                { text: 'Status', value: 'status' },
                { text: 'Actions', value: 'actions', width: 350 },
            ],
            items: [],
            options: this.getPaginateOptions(),
            total: 0,
            search: '',
            loading: true,
            integrationId: null
        }
    },

    created() {
        this.fetchPage()
    },

    computed: {
        params() {
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
            this.integrationId = item.id
            this.$refs.modal.onShow(
                'Delete Confirmation',
                'Are you sure you want to delete this account?',
                this.onDeleteConfirm
            );
        },

        onDeleteConfirm() {

            this.$http
                .delete('/api/integrations/' + this.integrationId)
                .then(({ data }) => {
                    EventBus.$emit('show-success', 'Successfully deleted an account.');

                    // remove current item selected
                    let index = this.items.findIndex(item => item.id == this.integrationId)
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
                .get('/api/integrations', {
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
                            label: item.attributes.label,
                            integration_type: item.attributes.integration_type.name,
                            account: item.attributes.account ? item.attributes.account.client_name : '',
                            frequency: item.attributes.frequency_mins,
                            last: item.attributes.last_run,
                            status: item.attributes.integration_status,
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