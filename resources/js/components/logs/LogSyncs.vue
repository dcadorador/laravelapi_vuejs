f<template>
    <div>
        <v-card>
            <v-card-title>
                <v-row>
                    <v-col>Sync record logs</v-col>
                    <v-col md="4">
                        <v-text-field
                            append-icon="search"
                            label="Search"
                            single-line
                            hide-details
                            style="margin-top: -10px;"
                            @keyup="onSearch($event)">
                        </v-text-field>
                    </v-col>
                </v-row>
            </v-card-title>
            <v-data-table
                v-if="items"
                :headers="headers"
                :options.sync="options"
                :server-items-length="pagination.total"
                :footer-props="{'items-per-page-options': [10, 25, 50, 100, -1]}"
                :items="items"
                :search="search"
                :loading="loading"
                :disable-sort="false">

                <!-- integration -->
                <template v-slot:item.integration="{ item }">
                  <router-link class="text-dark" :to="{name: 'integration edit', params:{id: item.integration.id}}">{{ item.integration.label }}</router-link>
                </template>

                <!-- actions buttons -->
                <template v-slot:item.actions="{ item }">
                    <v-btn x-small color="success" :to="'/logs/' + item.id">Review Record</v-btn>
                </template>

                <!-- styling record status -->
                <template v-slot:item.record_status="{ item }">
                  <v-chip v-if="item.record_status" x-small :color="getColor(item.record_status)" dark>{{ item.record_status }}</v-chip>
                </template>

                <!-- styling machship consignment status -->
                <template v-slot:item.machship_consignment_status="{ item }">
                  <v-chip v-if="item.machship_consignment_status" x-small :color="getColor(item.machship_consignment_status)" dark>{{ item.machship_consignment_status }}</v-chip>
                </template>

            </v-data-table>
            <v-progress-linear
                v-else
                indeterminate
                color="primary"
                class="mb-5">
            </v-progress-linear>
        </v-card>

        <Modal
            ref="modal"
            color="error"
            okText="delete" />

        <Modal
            ref="modalShow"
            color="primary"
            width="80%"
            :isOkayOnly="true" />
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

            loading: true,
            search: '',
            headers: [
                { text: 'Integration', value: 'integration', sortable: false },
                { text: 'Integration Sync ID', value: 'integration_sync_id' },
                { text: 'Source ID', value: 'source_id' },
                { text: 'Machship ID', value: 'machship_id' },
                { text: 'Machship Consignment Status', value: 'machship_consignment_status' },
                { text: 'Record Status', value: 'record_status' },
                { text: 'Created at', value: 'created_at' },
                { text: 'Actions', value: 'actions', sortable: false },
            ],
            items: [],
            timer: null,
            integrations: null,

            options: this.getPaginateOptions(),
            pagination: { total: 0 },
        }
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

    mounted() {
        // this.id = this.$route.params.id
        this.options.sortBy = ['id']
        this.options.sortDesc = ['true']
        this.fetchPage()
    },

    methods: {
        onSearch(e) {

            let text = e.target.value
            if (this.timer) {
                clearTimeout(this.timer);
                this.timer = null;
            }
            this.timer = setTimeout(() => {
                this.search = text
                this.options.page = 1
            }, 800);
        },

        onShowSource(item) {
            this.$refs.modalShow.onShow('Show Source Data', item.source_data);
        },

        onShowMachshipPayload(item) {
            this.$refs.modalShow.onShow('Show Machship Payload', item.machship_payload);
        },

        fetchPage() {
            this.loading = true
            let params = this.prepareParams(this.options, this.search)
            // add additional params for query purposes
            // params.integration_sync_id = this.id

            this.$http
                .get('/api/integrationrecords', { params })
                .then(({data}) => {
                    // map data to items
                    this.items = data.data.map(item => {
                        let map = item.attributes
                        map.id = item.id
                        return map
                    })

                    this.pagination = data.meta.pagination
                    this.loading = false
                })
                .catch(err => {
                    EventBus.$emit('show-error', err);
                    this.loading = false
                })

            this.$http
                .get('/api/integrations')
                .then( ({ data } ) => {
                    this.integrations = data.data.map(item => {
                        let map = item.attributes
                        map.id = item.id
                        console.log(map)
                        return map
                    })
                })
                .catch( err => {
                    EventBus.$emit('show-error', err);
                    this.loading = false
                })
        }
    }
}
</script>
