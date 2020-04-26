<template>
    <div class="mt-3 integration-mapping">
        <v-form
            v-if="items"
            ref="form"
            class="p-3 pt-0"
            @submit="onSubmit">
            <v-container fluid>
                <v-row>
                    <v-col>
                        <table class="table table-hover table-striped table-fs">
                            <thead>
                                <tr>
                                    <th width="200">Consignment Status</th>
                                    <th>Record Status</th>
                                    <th width="200">Update Source</th>
                                </tr>
                            </thead>
                            <tbody v-if="items.length > 0">
                                <tr v-for="(item, index) in items" :key="'status-mapping-' + index">
                                    <td>{{ item.machship_status }}</td>
                                    <td>
                                        <v-select
                                            dense
                                            outlined
                                            :full-width="false"
                                            :items="statusList"
                                            v-model="item.record_status"
                                        ></v-select>
                                    </td>
                                    <td>
                                        <v-checkbox 
                                            class="mx-auto"
                                            v-model="item.update_source"></v-checkbox>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="3">
                                        <p>No machship status mappings</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </v-col>
                </v-row>
                <v-row v-if="items.length > 0">
                    <v-col class="text-right">
                        <v-btn type="submit" small class="mr-1" color="primary" :disabled="isSubmit">
                            <span v-if="isSubmit">
                                <v-progress-circular
                                    indeterminate
                                    size="12"
                                    width="2"
                                    color="green darken-3">
                                </v-progress-circular>
                                Updating..
                            </span>
                            <span v-else>Update</span>
                        </v-btn>
                    </v-col>
                </v-row>
            </v-container>
        </v-form>
        <v-progress-linear
            v-else
            indeterminate
            color="primary"
            class="mb-5">
        </v-progress-linear>
    </div>
</template>

<script>
import { EventBus } from '../../event-bus.js'

export default {
    props: {
        // integration id
        id: { type: Number, default: null }
    },
    data() {
        return {
            items: null,
            isSubmit: false,
            statusList: []
        }
    },
    created() {
        this.fetchStatusList()
        this.fetchStatusMapping()
    },

    methods: {
        fetchStatusList() {
            this.$http
                .get('api/status/mapping/options')
                .then(({ data }) => {
                    console.log('status mapping options: ', data)
                    this.statusList = data.record_status.map(item => {
                        return {
                            text: item,
                            value: item
                        }
                    })
                })
        },

        fetchStatusMapping() {
            if (!this.id) {
                EventBus.$emit('show-error', 'Invalid integration id')
                return
            }

            this.$http
                .get('api/status/mapping', {
                    params: {
                        integration_id: this.id,
                        limit: -1
                    }
                })
                .then(({ data }) => {

                    this.items = data.data.map(item => {
                        let obj = item.attributes
                        obj.id = item.id
                        return obj
                    })
                })
        },

        onSubmit(e) {
            e.preventDefault()
            this.isSubmit = true
            this.$http
                .post('api/status/mapping/bulk', this.items)
                .then(({ data }) => {
                    console.log('has data: ', data)
                    this.isSubmit = false
                    EventBus.$emit('show-success', 'Successfully updated status mapper')
                })
                .catch(err => {
                    EventBus.$emit('show-error', err)
                    this.isSubmit = false
                })
        }
    }
}
</script>