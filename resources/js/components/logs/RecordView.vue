<template>
    <div class="view-record">
        <v-card>
            <v-card-title>
                View record # {{ record_id }}
            </v-card-title>

            <div v-if="record">
                <v-container fluid>
                    <v-row>
                        <v-col>
                            <h4>{{ record.integration.label }}</h4>

                            <div class="d-flex">
                                <p>
                                    Machship Consignment Status: 
                                    <v-chip v-if="record.machship_consignment_status" :color="getColor(record.machship_consignment_status)" dark>
                                        {{ record.machship_consignment_status }}
                                    </v-chip>
                                </p>
                                <v-spacer></v-spacer>
                                <p>
                                    Record Status : 
                                    <v-chip v-if="record.record_status" :color="getColor(record.record_status)" dark>
                                        {{ record.record_status }}
                                    </v-chip>
                                </p>
                            </div>

                            <!-- re actions -->
                            <div v-if="hasReActionOptions">
                                <!-- RE-PULL ACTION  -->
                                <v-btn x-small color="error" @click="onRepull" :disabled="is_repulling">
                                    <span v-if="is_repulling">
                                        <v-progress-circular
                                            indeterminate
                                            size="12"
                                            width="2"
                                            color="error darken-3">
                                        </v-progress-circular>
                                        Re-Pulling...
                                    </span>
                                    <span v-else>
                                        Re-Pull
                                    </span>
                                </v-btn>

                                <!-- RE PROCESS ACTION -->
                                <v-btn x-small color="error" @click="onReprocess" :disabled="is_reprocessing">
                                    <span v-if="is_reprocessing">
                                        <v-progress-circular
                                            indeterminate
                                            size="12"
                                            width="2"
                                            color="error darken-3">
                                        </v-progress-circular>
                                        Re-Processing...
                                    </span>
                                    <span v-else>
                                        Re-Process
                                    </span>
                                </v-btn>

                                <!-- RE PUSH ACTION -->
                                <v-btn x-small color="error" @click="onRepush" :disabled="is_repushing">
                                    <span v-if="is_repushing">
                                        <v-progress-circular
                                            indeterminate
                                            size="12"
                                            width="2"
                                            color="error darken-3">
                                        </v-progress-circular>
                                        Re-Pushing...
                                    </span>
                                    <span v-else>
                                        Re-Push
                                    </span>
                                </v-btn>
                            </div>
                            <v-divider class="mt-5"></v-divider>
                        </v-col>
                    </v-row>

                    <!-- source data and machship payload -->
                    <v-row>
                        <v-col md="6">
                            <b>Source data</b>
                            <pre class="record-data">{{ record.source_data }}</pre>
                        </v-col>
                        <v-col md="6">
                            <b>Machship Payload</b>
                            <pre class="record-data">{{ record.machship_payload }}</pre>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col md="6">
                            <b>Debug Logs</b>
                            <pre class="record-data">{{ debugLogs }}</pre>
                        </v-col>
                        <v-col md="6">
                            <b>Sync Logs</b>
                            <pre class="record-data">{{ syncLogs }}</pre>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
            <v-progress-linear
                v-else
                indeterminate
                color="primary"
                class="mb-5">
            </v-progress-linear>
        </v-card>
    </div>
</template>

<script>
import { EventBus } from '../../event-bus.js'
export default {

    data() {
        return {
            record_id: '',
            record: null,
            is_repulling: false,
            is_repushing: false,
            is_reprocessing: false,
        }
    },

    computed: {
        debugLogs() {
            return this.record.debug_logs ? 
                this.record.debug_logs.sort((a, b) => {
                    return new Date(b.created_at) - new Date(a.created_at)
                })
                .map(log => {
                    return {
                        step: log.sync_step,
                        created: log.created_at,
                        data: log.data
                    }
                }) : 'no debug logs.'
        },
        syncLogs() {
            return this.record.sync_logs ? 
                this.record.sync_logs.sort((a, b) => {
                    return new Date(b.created_at) - new Date(a.created_at)
                })
                .map(log => {
                    return {
                        step: log.step,
                        created: log.created_at,
                        data: log.data,
                        result: log.result
                    }
                }) : 'no sync logs.'
        },
        hasReActionOptions() {
            return this.$store.getters.isAdmin
        },
    },

    mounted() {
        this.record_id = parseInt(this.$route.params.id)
        this.$http
            .get('/api/integrationrecords/' + this.record_id)
            .then(({ data }) => {
                console.log('data : ', data)
                this.record = data.data.attributes
            })
    },

    methods: {

        onReAction(mode) {
            // we need to control re-actions
            if (
                this.is_repulling ||
                this.is_repushing ||
                this.is_reprocessing
            ) {
                return;
            }

            let checker = false;

            switch (mode) {
                case 'repull': checker = 'is_repulling'; break;
                case 'repush': checker = 'is_repushing'; break;
                case 'reprocess': checker = 'is_reprocessing'; break;
            }

            this[checker] = true
            this.$http
                .post('/api/integrationrecords/' + mode, {id: this.record_id})
                .then(({ data }) => {
                    if (data.status) {
                        EventBus.$emit('show-success', 'Record has been ' + mode + ' successfully')
                        setTimeout(() => {
                            this.$router.go()
                        }, 1000)
                    } else {
                        EventBus.$emit('show-error', data.message)    
                        this[checker] = false
                    }
                })
                .catch(err => {
                    EventBus.$emit('show-error', err)
                    this[checker] = false
                })
        },

        // record view execute re-pull record
        onRepull() {
            this.onReAction('repull')
        },

        // record view execute re-process record
        onReprocess() {
            this.onReAction('reprocess')
        },

        // record view execute re-push record
        onRepush() {
            this.onReAction('repush')
        },
    }
}

</script>