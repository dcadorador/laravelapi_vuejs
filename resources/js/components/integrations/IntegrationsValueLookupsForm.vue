<template>
    <div class="mt-3 integration-mapping">

        <div class="add-lookup-wrapper p-3 d-flex">
            <v-spacer></v-spacer>
            <div class="add-lookup">
                <h5 class="subtitle-2">Machship Fields</h5>
                <div class="d-flex">
                    <v-combobox
                        outlined
                        dense
                        :items="machshipFields"
                        v-model="selectField"
                        class="mr-3">
                    </v-combobox>
                    <v-btn @click="onAddLookupTable" color="primary">Add Lookup Table</v-btn>
                </div>
            </div>
        </div>


        <v-form
            v-if="items"
            ref="form"
            v-model="valid"
            class="p-3 pt-0"
            @submit="onSubmit">

            <v-container fluid v-if="Object.keys(items).length > 0">
                <v-row 
                    v-for="(item, index) in items"
                    :key="'item-' + index"
                    class="mt-5">
                    <!-- machship_field -->
                    <v-col sm="12">
                        <div class="pb-3 d-flex">
                            <v-spacer></v-spacer> 
                            <v-btn fab dark color="orange" @click="onRemoveLookupTable(index)">
                                <v-icon dark>mdi-minus</v-icon>
                            </v-btn>
                        </div>
                        <label class="mt-3"><b>{{ index }}</b> Lookup Table</label>
                        <table class="table-valuelookups table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>From Label</th>
                                    <th>To</th>
                                    <th>To Label</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(lookup, i) in item"
                                    :key="'item-lookup-' + i">
                                    <td>
                                        <v-text-field flat dense v-model="lookup.from_value">
                                        </v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field flat dense v-model="lookup.from_label">
                                        </v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field flat dense v-model="lookup.to_value">
                                        </v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field flat dense v-model="lookup.to_label">
                                        </v-text-field>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <v-text-field :ref="'new-' + index + '-from_value'" flat dense @change="onChangeNew($event, 'from_value', index)"></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field :ref="'new-' + index + '-from_label'" flat dense @change="onChangeNew($event, 'from_label', index)"></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field :ref="'new-' + index + '-to_value'" flat dense @change="onChangeNew($event, 'to_value', index)"></v-text-field>
                                    </td>
                                    <td>
                                        <v-text-field :ref="'new-' + index + '-to_label'" flat dense @change="onChangeNew($event, 'to_label', index)"></v-text-field>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <div class="mt-5 text-right">
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
                        </div>
                    </v-col>
                </v-row>
            </v-container>
            <p v-else>No value lookups at the moment.</p>
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
import Vue from 'vue'
import * as easings from 'vuetify/es5/services/goto/easing-patterns'

export default {
    props: {
        id: { type: Number, default: null },
        direction: { type: String, default: 'from_machship' }
    },

    data() {
        return {
            isSubmit: false,
            valid: false,
            items: [],
            machshipFields: [],
            selectField: '',
            type: 'number'
        }
    },

    created() {
        this.items = null
        this.fetchIntegration()
        this.fetchOptions()
    },

    computed: {
        target () {
            const value = this[this.type]
            if (!isNaN(value)) return Number(value)
            else return value
          }
    },

    methods: {

        onAddLookupTable() {
            if (!this.items[this.selectField]) {
                // Object.assign(this.items, {[this.selectField]: []})
                // let items = this.items[this.selectField]
                Vue.set(this.items, this.selectField, [])
                setTimeout(() => {
                    let refs = this.$refs['new-' + this.selectField + '-from_value'][0]
                    this.$vuetify.goTo(refs, {
                        duration: 300,
                        offset: 0,
                        easing: 'easeInOutCubic',
                    })
                    refs.focus()
                }, 100)
            }
        },

        onRemoveLookupTable(machship_field) {

            // we need to delete this by bulk
            let items = this.items[machship_field]
                            .filter(item => item.id)
                            .map(item => item.id)

            this.$http
                .post('api/valuelookups/removes',  items)
                .then(res => {
                    Vue.delete(this.items, machship_field)
                })
        },

        fetchOptions() {
            this.$http
                .get('api/valuelookups/options/' + this.id)
                .then(({ data }) => {
                    this.machshipFields = []
                    data.machship_fields.sort()
                    this.machshipFields = data.machship_fields
                    this.selectField = this.machshipFields[0]
                })
        },

        onChangeNew(text, field, machship_field) {
            // validate text changed
            if (text == "") {
                return
            }

            let new_item = {
                _id: Math.random().toString(36).substring(7),
                from_value: '',
                from_label: '',
                to_value: '',
                to_label: '',
                integration_id: this.id,
                machship_field: machship_field
            };

            // this.items[machship_field].push(Object.assign(new_item, {[field]: text}))
            this.items[machship_field].push(Object.assign(new_item, {[field]: text}))
            let items = Object.assign({}, this.items[machship_field])
            // let items = Object.assign(new_item, {[field]: text})
            Vue.set(this.items, 'test', [])
            delete this.items['test']
            this.$refs['new-' + machship_field + '-' + field][0].reset()
            console.log(' new items hahaha' , items)
        },

        onSubmit(e) {
            e.preventDefault()
            if (this.$refs.form.validate()) {
                this.isSubmit = true
                this.$http
                    .post('/api/valuelookups/bulk', {
                        direction: this.direction,
                        items: this.items
                    })
                    .then(({ data }) => {
                        EventBus.$emit('show-success', 'Successfully saved value lookups.')
                        console.log('success bulk save: ', data)
                        this.prepareMapItems(data)
                        this.isSubmit = false
                    })
                    .catch(err => {
                        EventBus.$emit('show-error', err)
                        this.isSubmit = false
                    })
            }
        },

        fetchIntegration() {
            this.$http
            .get('/api/valuelookups/' + this.direction + '/' + this.id)
            .then(({ data }) => {
                
                console.log('data is : ', data)
                this.prepareMapItems(data)
                
            })
            .catch(err => {
                EventBus.$emit('show-error', err);
            })
        },

        prepareMapItems(data) {
            this.items = {}
            // gives time for each item to render
            data.forEach((item, i) => {
                if (this.items[item.machship_field]) {
                    this.items[item.machship_field].push(item)
                } else {
                    // this.items.push(item.machship_field)
                    Object.assign(this.items, {[item.machship_field]: []})
                    this.items[item.machship_field].push(item)
                }
            })
        }
    }
}
</script>