<template>
    <div class="mt-3 p-3" color="light">
        <h5 class="subtitle-2">
            Source Data Filters
        </h5>

        <div v-if="items">
            <v-form
                ref="form"
                v-model="form">
                
                <v-expansion-panels class="mt-5 mb-5">
                    <v-expansion-panel
                        v-for="(item, index) in items"
                        :key="item.id"
                        class="bg-grey">
                        <v-expansion-panel-header>
                            Source Data Filter # {{ (index + 1) }}
                            <template v-slot:actions>
                                <v-icon>$expand</v-icon>
                            </template>
                        </v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <v-container fluid v-for="(option, optIndex) in item" :key="'form-' + optIndex">
                                <v-hover v-slot:default="{ hover }">
                                    <v-row align="center">
                                        <v-col lg="3">
                                            <v-combobox
                                                v-model="option.filter_key"
                                                :items="filterKeys"
                                                menu-props="auto"
                                                label="Filter field"
                                                :rules="[v => !!v || 'Filter key required']"
                                                @change="() => onFilterKeyChange(option)">
                                            </v-combobox>
                                        </v-col>
                                        <v-col lg="6">
                                            <v-combobox
                                                v-if="option.filterValues"
                                                v-model="option.filter_value"
                                                :items="option.filterValues"
                                                menu-props="auto"
                                                label="Filter value"
                                                :rules="[v => !!v || 'Filter value required']">
                                            </v-combobox>
                                            <v-text-field
                                                v-else
                                                v-model="option.filter_value"
                                                type="text"
                                                label="Filter value"
                                                :rules="[v => !!v || 'Filter value is required']">
                                            </v-text-field>
                                        </v-col>
                                        
                                        <v-col sm="2" class="ml-auto text-right">
                                            <span class="ic-remove clickable" @click="onRemove(item, optIndex)">
                                                <i class="fa fa-times-circle fa-2x"></i>
                                            </span>
                                        </v-col>
                                    </v-row>
                                </v-hover>
                            </v-container>
                            <div class="d-flex">
                                <v-btn small color="primary" class="mr-3" @click="onAddMoreOption(item)">Add more</v-btn>
                            </div>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-expansion-panels>
            </v-form>

            <div class="mt-3 d-flex">
                <v-btn small @click="onReset" color="default" :disabled="isSubmit">reset filter</v-btn>
                <v-spacer></v-spacer>
                <v-btn class="mr-1" small @click="onAddMore">Add more filter</v-btn>
                <v-btn small color="primary" @click="onSubmit" :disabled="isSubmit">
                    <span v-if="isSubmit">
                        <v-progress-circular
                          indeterminate
                            size="12"
                            width="2"
                          color="green darken-3">
                        </v-progress-circular>
                        saving
                    </span>
                    <span v-else>save</span>
                </v-btn>
            </div>
        </div>
        <v-progress-linear
            v-else
            indeterminate
            color="primary"
            class="mb-5">
        </v-progress-linear>
    </div>
</template>

<script>
import { EventBus } from '@/js/event-bus.js'

export default {
    props: {
        integrationId: { type: Number, default: null },
        integrationFilters: { type: Array, default: [] }
    },

    data() {
        return {
            items: [],
            filterOptions: null,
            filterKeys: [],
            form: null,
            isSubmit: false
        }
    },

    mounted() {
        let items = this.integrationFilters
        if (!items || items.length <= 0) {
            this.onReset()
        } else {
            this.prepareItems(this.integrationFilters)
        }

        this.onGetOptions()
    },

    methods: {
        onAddMore() {
            this.items.push([{
                filter_key: '',
                filter_value: '',
                integration_id: this.integrationId
            }])
        },

        onAddMoreOption(item) {
            console.log('more item option', item)
            // gets the parent id
            let index = item.findIndex(ii => ii.id && ii.integration_source_filter_id == null)

            item.push({
                filter_key: '',
                filter_value: '',
                integration_id: this.integrationId,
                integration_source_filter_id: item[index].id
            })
        },

        onRemove(item, index) {

            if (Number.isInteger(item[index].id)) {
                this.$http
                    .delete('/api/integrationfilters/' + item[index].id)
                    .then(() => {
                        EventBus.$emit('show-success', 'Successfully deleted integration filter.');
                    })
                    .catch(err => {
                        EventBus.$emit('show-error', err)
                    })
            }

            item.splice(index, 1)

            if (item.length == 0) {
                let removeIndex = this.items.findIndex(ii => ii == item)
                this.items.splice(removeIndex, 1)
            }
        },

        async onSubmit(e) {
            e.preventDefault()
            if (this.$refs.form.validate()) {
                this.isSubmit = true

                this.$http
                    .post('/api/filters/bulk', this.items)
                    .then(({ data }) => {
                        EventBus.$emit('show-success', 'Successfully saved filter.')
                        this.isSubmit = false
                        this.prepareItems(data.data.attributes.filters)
                    })
                    .catch(err => {
                        EventBus.$emit('show-error', err)
                        this.isSubmit = false
                    })
            }
        },

        onReset() {
            this.isSubmit = true
            this.$http
                .get('/api/filters/reset/' + this.integrationId)
                .then(({ data }) => {
                    EventBus.$emit('show-success', 'Successfully resets source filters.')
                    this.isSubmit = false
                    this.prepareItems(data.data.attributes.filters)
                })
                .catch (err => {
                    EventBus.$emit('show-error', err)
                    this.isSubmit = false
                })
        },

        onGetOptions() {
            this.$http
                .get('/api/filters/options/' + this.integrationId)
                .then(({ data }) => {
                    this.filterKeys = Object.keys(data)
                    this.filterOptions = data
                })
        },

        onFilterKeyChange(item) {
            if (this.filterOptions && this.filterOptions[item.filter_key]) {
                item.filterValues = this.filterOptions[item.filter_key]
            }
        },

        prepareItems(items) {
            if (!items || items.length <= 0) {
                return
            }

            let newItems = [];
            items.forEach(filters => {
                if (filters.integration_source_filter_id === null) {
                    newItems.push([filters])
                } else {
                    let index = newItems.findIndex(e => e && e[0].id === filters.integration_source_filter_id)
                    if (index >= 0) {
                        newItems[index].push(filters);
                    }
                }
            })
            this.items = newItems
            console.log('items is :::: ', this.items)
        }
    }
}
</script>