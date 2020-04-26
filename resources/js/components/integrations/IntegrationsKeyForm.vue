<template>
    <v-form 
        ref="keyform"
        v-if="items"
        v-model="keyForm"
        @submit="onKeyFormSubmit">

        <div
            v-for="(item, index) in itemKeys"
            :key="item.id">
            <v-hover v-slot:default="{ hover }">
                <v-row align="center" :class="{'grey lighten-5 mb-3' : isMultiple}">

                    <!-- key type -->
                    <v-col md="6" lg="3">
                        <v-text-field
                            v-model="item.key_type"
                            type="text"
                            label="Key Type"
                            :disabled="isFieldDisable(item)"
                            :rules="[v => !!v || 'Key type is required']">
                        </v-text-field>
                    </v-col>

                    <!-- key data -->
                    <v-col lg="3">
                        <v-text-field
                            v-model="item.key_data"
                            type="text"
                            label="Key Data"
                            :rules="[v => !!v || 'Key data is required']">
                        </v-text-field>
                    </v-col>

                    <!-- key expiry -->
                    <v-col md="12" lg="3">
                        <v-menu
                            v-model="item.showDate"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            transition="scale-transition"
                            offset-y
                            min-width="290px">
                            <template v-slot:activator="{ on }">
                                <!-- date -->
                                <v-text-field
                                    v-model="item.expiry"
                                    label="Expiry date"
                                    prepend-icon="event"
                                    clearable
                                    readonly
                                    v-on="on"
                                ></v-text-field>
                            </template>
                            <v-date-picker v-model="item.expiry" @input="item.showDate = false"></v-date-picker>
                        </v-menu>
                    </v-col>
                    <v-col v-if="isMultiple" sm="2" class="ml-auto text-right">
                        <span class="ic-remove clickable" @click="onRemove(item)">
                            <i class="fa fa-times-circle fa-2x"></i>
                        </span>
                        
                    </v-col>
                </v-row>
            </v-hover>
        </div>
    </v-form>
</template>

<script>
import { EventBus } from '../../event-bus.js'
import moment from 'moment'

export default {

    props: {
        integrationId: { type: Number, default: null },
        formItems:     { type: Array, default: null },
        isMultiple:    { type: Boolean, default: false },
    },

    data() {
        return {
            // machship key form
            keyForm: false,
            items: this.formItems,

            fixedKeys: [
                'machship_token'
            ],
        }
    },

    computed: {
        itemKeys() {
            let itemKeys = this.items.filter(key => {
                let forMultiple = this.isMultiple && key.key_type != 'machship_token'
                let forMachship = !this.isMultiple && key.key_type == 'machship_token'
                return forMachship || forMultiple
            })

            itemKeys = itemKeys.map(key => {
                if (key.expiry != '' && key.expiry != null) {
                    key.expiry = moment(key.expiry).format('YYYY-MM-DD')
                }

                return key
            })

            return itemKeys
            // TESTING
        }
    },

    created() {
        // this.items = this.formItems
    },

    methods: {
        onRemove(itemKey) {
            if (this.isMultiple) {
                let index = this.items.findIndex(item => item == itemKey)
                this.items.splice(index, 1)
            }

            if (Number.isInteger(itemKey.id)) {
                this.$http
                    .delete('/api/integrationkeys/' + itemKey.id)
                    .then(() => {
                        EventBus.$emit('show-success', 'Successfully deleted integration key.');
                    })
                    .catch(err => {
                        EventBus.$emit('show-error', err)
                    })
            }
        },

        async onKeyFormSubmit() {

            // check item keys input
            if (this.itemKeys == null || this.itemKeys.length == 0) {
                return
            }
            

            if (this.$refs.keyform.validate()) {
                await this.$http
                    .post('/api/integrationkeys/bulk', this.items)
                    .then(({ data }) => {
                        EventBus.$emit('show-success', 'Successfully saved integrations.')
                        console.log('success bulk save: ', data)
                        this.items = data.data.attributes.keys
                    })
                    .catch(err => {
                        EventBus.$emit('show-error', err)
                    })
            }
        },

        onKeyFormAddMore() {
            this.items.push({
                key_type: '',
                key_data: '',
                expiry: '',
                showDate: false,
                integration_id: this.integrationId
            })
        },

        isFieldDisable(item) {
            return this.fixedKeys.findIndex(key => item.key_type == key) >= 0
        }
    }
}
</script>