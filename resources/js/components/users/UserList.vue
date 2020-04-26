<template>
    <div>
        <v-card>
            <v-card-title>
                <i class="fas fa-users mr-3"></i> User Lists
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
                    <v-btn small color="success" :to="{ name: 'user edit', params: {id: item.id} }" >Edit</v-btn>
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
import Modal from '../Modal'
import { EventBus } from '../../event-bus.js'

export default {
    components: {
        Modal
    },
    data() {
        return {

            headers: [
                { text: 'ID', value: 'id' },
                { text: 'Name', value: 'name' },
                { text: 'Email', value: 'email' },
                { text: 'Roles', value: 'roles' },
                { text: 'Actions', value: 'actions', width: 250, sortable: false, },
            ],
            search: '',
            searching: '',

            loading: true,
            items: null,
            dialogConfirm: false,
            page: 0,
            total: 0,
            length: 0,
            size: 0,
            userId: 0,

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

    watch:{
        params(val, oldVal) {
            // this is to avoid redundant request
            if (JSON.stringify(val) != JSON.stringify(oldVal)) {
                this.fetchPage()
            }
        },
    },

    methods: {
        // deleting user
        onDelete(user) {
            this.dialogConfirm = true
            this.userId = user.id
            this.$refs.modal.onShow(
                'Delete Confirmation',
                'Are you sure you want to delete this user?',
                this.onDeleteConfirm
            );
        },

        onDeleteConfirm() {
            this.$http
                .delete('/api/users/' + this.userId)
                .then(({ data }) => {
                    EventBus.$emit('show-success', 'Successfully deleted a user.');
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
                .get('/api/users', {
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
                            name: item.attributes.name,
                            email: item.attributes.email,
                            roles: item.attributes.roles.join(', ')
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