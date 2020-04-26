<template>
    <div class="mt-3 integration-mapping">

        <v-form
            v-if="items && machshipFields.length > 0"
            ref="form"
            v-model="valid"
            @submit="onSubmit">

            <v-container fluid>
                <v-row>

                    <!-- Machship fields -->
                    <v-col>
                        <table class="table table-mapping">
                            <thead>
                                <tr>
                                    <th>
                                        <h5 class="subtitle-2">Machship Field</h5>
                                    </th>
                                    <th colspan="2" class="bg-orange-panel">
                                        <h5 class="subtitle-2">Field Mapper</h5>
                                    </th>
                                    <th width="10"></th>
                                    <th colspan="2" class="bg-orange-panel">
                                        <h5 class="subtitle-2">Data Conversion</h5>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td class="bg-orange-panel pb-0">Map Type</td>
                                    <td class="bg-orange-panel pb-0">Source Field</td>
                                    <td></td>
                                    <td class="bg-orange-panel pb-0">Conversion Type</td>
                                    <td class="bg-orange-panel pb-0">Conversion Value</td>
                                </tr>

                                <!-- machship fields -->
                                <tr
                                    v-for="(field, index) in machshipFields"
                                    :key="'field-' + index"
                                    class="p-3"
                                    :set="item = findItemByField(field)">
                                    <td class="pt-5">{{ field }}</td>
                                    <td class="bg-orange-panel" width="120">
                                        <v-combobox
                                            dense
                                            :items="mapTypes"
                                            :value="item.map_type"
                                            @change="onChangeItemMapType($event, field)">
                                        </v-combobox>
                                    </td>
                                    <td class="bg-orange-panel">
                                        <v-combobox
                                            dense
                                            :value="item.source_field"
                                            :items="sourceFields"
                                            @change="onChangeSourceFields($event, field)"
                                        />
                                    </td>
                                    <td></td>
                                    <td class="bg-orange-panel" width="120">
                                        <v-combobox
                                            dense
                                            :items="dataConverionTypes"
                                            menu-props="auto"
                                            :value="item.data_conversion_type"
                                            @change="onChangeDataConversionType($event, field)">
                                        </v-combobox>
                                    </td>
                                    <td class="bg-orange-panel">
                                        <v-text-field
                                            dense
                                            :value="item.data_conversion_value"
                                            @change="onChangeDataConversionValue($event, field)">
                                        </v-text-field>
                                    </td>
                                </tr>

                                <!-- items -->
                                <tr class="item-row-header mb-3">
                                    <td class="pt-5">
                                        <h5 class="mt-5 subtitle-2">Item *</h5>
                                    </td>
                                    <td colspan="2" class="bg-orange-panel"></td>
                                    <td></td>
                                    <td colspan="2" class="text-right bg-orange-panel"></td>
                                </tr>
                                <tr
                                    v-for="(field, index) in machshipItemFields"
                                    :key="'itemfield-' + index"
                                    class="p-1"
                                    :set="item = findItemByField(field)">
                                    <td class="pt-5">{{ field }}</td>
                                    <td class="bg-orange-panel" width="120">
                                        <v-combobox
                                            dense
                                            :items="mapTypes"
                                            :value="item.map_type"
                                            @change="onChangeItemMapType($event, field)">
                                        </v-combobox>
                                    </td>
                                    <td class="bg-orange-panel">
                                        <v-combobox
                                            dense
                                            :value="item.source_field"
                                            :items="sourceFields"
                                            @change="onChangeSourceFields($event, field)"
                                        />
                                    </td>
                                    <td></td>
                                    <td class="bg-orange-panel" width="120">
                                        <v-combobox
                                            dense
                                            :items="dataConverionTypes"
                                            menu-props="auto"
                                            :value="item.data_conversion_type"
                                            @change="onChangeDataConversionType($event, field)">
                                        </v-combobox>
                                    </td>
                                    <td class="bg-orange-panel">
                                        <v-text-field
                                            dense
                                            :value="item.data_conversion_value"
                                            @change="onChangeDataConversionValue($event, field)">
                                        </v-text-field>
                                    </td>
                                </tr>

                            </tbody>

                            <!-- Optional Items -->
                            <tfoot>
                                <template v-for="row in item_rows">
                                    <tr class="item-row-header">
                                        <td class="pt-5">
                                            <h5 class="mt-5 subtitle-2">Item {{ row }}</h5>
                                        </td>
                                        <td colspan="2" class="bg-orange-panel">
                                        </td>
                                        <td></td>
                                        <td colspan="2" class="text-right bg-orange-panel">
                                            <v-btn fab dark color="orange" @click="onRemoveItemRow(row)">
                                              <v-icon dark>mdi-minus</v-icon>
                                            </v-btn>
                                        </td>
                                    </tr>
                                    <tr
                                        v-for="(field, index) in getItemsByRow(row)"
                                        :key="'itemfield-' + row + '-' + index"
                                        :set="item = findItemByField(field)">
                                        <td class="pt-5">{{ field }}</td>
                                        <td class="bg-orange-panel" width="120">
                                            <v-select
                                                dense
                                                :items="mapTypes"
                                                :value="item.map_type"
                                                @change="onChangeItemMapType($event, field)">
                                            </v-select>
                                        </td>
                                        <td class="bg-orange-panel">
                                            <v-combobox
                                                dense
                                                :value="item.source_field"
                                                :items="sourceFields"
                                                @change="onChangeSourceFields($event, field)"
                                            />
                                        </td>
                                        <td></td>
                                        <td class="bg-orange-panel" width="120">
                                            <v-select
                                                dense
                                                :items="dataConverionTypes"
                                                menu-props="auto"
                                                :value="item.data_conversion_type"
                                                @change="onChangeDataConversionType($event, field)">
                                            </v-select>
                                        </td>
                                        <td class="bg-orange-panel">
                                            <v-text-field
                                                dense
                                                :value="item.data_conversion_value"
                                                @change="onChangeDataConversionValue($event, field)">
                                            </v-text-field>
                                        </td>
                                    </tr>
                                </template>
                            </tfoot>
                        </table>
                    </v-col>
                </v-row>
            </v-container>
            <div class="mt-3 p-3">
                <v-btn class="mx-2" fab dark color="orange" @click="onAddItemRow">
                  <v-icon dark>mdi-plus</v-icon>
                </v-btn>
            </div>
            <div class="mt-5 p-3 d-flex">
                <v-spacer></v-spacer>
                <v-btn type="submit" small class="mr-1" color="primary" :disabled="isSubmit">
                    <span v-if="isSubmit">
                        <v-progress-circular
                            indeterminate
                            size="12"
                            width="2"
                            color="green darken-3">
                        </v-progress-circular>
                        updating...
                    </span>
                    <span v-else>Update</span>
                </v-btn>
            </div>
        </v-form>
        <v-progress-linear
            v-else
            indeterminate
            color="primary">
        </v-progress-linear>
    </div>
</template>

<script>
import { EventBus } from '../../event-bus.js'

export default {
    props: {
        id: { type: Number, default: null },
        direction: { type: String, default: 'from_machship' }
    },

    data() {
        return {
            valid: false,
            isSubmit: false,
            items: null,
            item_test: {
                data_direction: '',
                machship_field: '',
                map_type: '',
                source_field: '',
                data_conversion_type: '',
                integration_id: this.id
            },
            item_rows: [],

            dataDirections: [],
            dataConverionTypes: [],
            mapTypes: [],

            machshipFields: [],
            machshipItemFields: [],
            sourceFields: []
        }
    },

    mounted() {
        this.fetchIntegration()
        this.fetchFieldMapperOptions()
    },

    methods: {

        onAddItemRow() {
            if (this.item_rows.length > 0) {
                let last_row = this.item_rows[this.item_rows.length -1] 
                this.item_rows.push(parseInt(last_row) + 1)
                return
            }

            this.item_rows = [0]
        },

        onRemoveItemRow(row) {
            let items = this.items.filter(item => {
                return item.machship_field.indexOf("items.[" + row) >= 0 && item.id
            }).map(item => {
                return item.id
            })

            let index = this.item_rows.indexOf(row)
            this.item_rows.splice(index, 1)

            if (items.length > 0) {
                this.$http
                    .post('/api/fieldmappers/removes', items)
                    .then(res => {
                        console.log('result is : ', res)

                        // we need to delete original client items too
                        this.items = this.items.filter(item => {
                            return item.machship_field.indexOf("items.[" + row) < 0
                        })
                    })
            }
        },

        onChangeItemMapType(e, field) {
            console.log('items : ', field)
            let index = this.items.findIndex(item => {
                return item.machship_field == field
            })

            this.items[index].map_type = e.value
        },

        onChangeSourceFields(e, field) {
            let index = this.items.findIndex(item => {
                return item.machship_field == field
            })

            this.items[index].source_field = e
        },

        onChangeDataConversionType(e, field) {
            let index = this.items.findIndex(item => {
                return item.machship_field == field
            })

            this.items[index].data_conversion_type = e.value
        },

        onChangeDataConversionValue(e, field) {
            let index = this.items.findIndex(item => {
                return item.machship_field == field
            })

            this.items[index].data_conversion_value = e
        },

        findItemByField(machship_field) {
            let index = this.items.findIndex(item => {
                return item.machship_field == machship_field
            })

            if (index >= 0) {
                return this.items[index]
            } else {
                return this.onAddMapping(machship_field)
            }
        },

        getItemsByRow(row) {
            let field_row = []
            this.machshipItemFields.forEach(field => {
                let field_split = field.split(".")
                let field_item = field_split[0] + '.[' + row + '].' + field_split[1]
                field_row.push(field_item)
            })
            return field_row
        },

        onSubmit(e) {
            e.preventDefault()

            if (this.$refs.form.validate()) {
                this.isSubmit = true
                this.$http
                    .post('/api/fieldmappers/bulk', this.items)
                    .then(({ data }) => {
                        EventBus.$emit('show-success', 'Successfully saved integrations.')
                        console.log('success bulk save: ', data)
                        this.items = data
                        this.isSubmit = false
                    })
                    .catch(err => {
                        EventBus.$emit('show-error', err)
                        this.isSubmit = false
                    })
            } else {
                EventBus.$emit('show-error', 'Fail! Please check all required fields')
            }
        },

        onAddMapping(field) {
            this.item_test.machship_field = field
            this.item_test.data_direction = this.direction
            let id = Math.random().toString(36).substring(7)
            let new_item = Object.assign({_id: id}, this.item_test)
            this.items.push(new_item)
            return new_item
        },


        fetchIntegration() {
            this.$http
            .get('/api/fieldmappers/' + this.direction + '/' + this.id)
            .then(({ data }) => {
                this.items = data
                this.checkMoreItems()
            })
            .catch(err => {
                EventBus.$emit('show-error', err);
            })
        },

        fetchFieldMapperOptions() {
            this.$http
                .get('api/fieldmappers/options/' + this.id)
                .then(({ data }) => {
                    // console.log('options are : ', data)
                    for (const index in data.options.directions) {
                        this.dataDirections.push({ 
                            value: index,
                            text: data.options.directions[index]
                        })
                    }

                    for (const index in data.options.conversion_types) {
                        this.dataConverionTypes.push({ 
                            value: index,
                            text: data.options.conversion_types[index]
                        })
                    }
                    
                    for (const index in data.options.map_types) {
                        this.mapTypes.push({ 
                            value: index,
                            text: data.options.map_types[index]
                        })
                    }


                    data.machship_fields.sort()

                    for (const index in data.machship_fields) {

                        if (data.machship_fields[index].indexOf("items.") >= 0) {
                            this.machshipItemFields.push(data.machship_fields[index])
                        } else {
                            setTimeout(function() {
                                this.machshipFields.push(data.machship_fields[index])
                            }.bind(this), (index * 30))
                        }
                    }

                    for (const index in data.source_fields) {
                        this.sourceFields.push(data.source_fields[index])
                    }
                })
        },

        checkMoreItems() {
            // TODO to improve on getting more items row
            this.items.forEach(item => {
                if (item.machship_field.indexOf("items.[") >= 0) {
                    let split_items = item.machship_field.split("items.[")
                    if (split_items.length > 1) {
                        let split_number = split_items[1].split("].")
                        if (!this.item_rows[split_number[0]]) {
                            this.item_rows.push(split_number[0])
                        } else {
                            console.log('this item rows : ', this.item_rows[split_number[0]])
                        }
                    }
                }
            })

            console.log('so the item rows is : ', this.item_rows)
        }

    }
}
</script>