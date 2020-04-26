<template>
    <div class="mt-3 p-3" color="light">
        <h5 class="subtitle-2">
            Integration Meta Configs
        </h5>

        <v-form
            ref="metaForm"
            v-if="items"
            v-model="form">
            <v-container fluid>
                <div
                    v-for="(item, index) in items"
                    :key="item.id">
                    <v-hover v-slot:default="{ hover }">
                        <v-row align="center" class="grey lighten-5 mb-3">
                            <v-col lg="3">
                                <v-text-field
                                    v-model="item.meta_key"
                                    type="text"
                                    label="Meta key"
                                    :rules="[v => !!v || 'Meta key is required']">
                                </v-text-field>
                            </v-col>
                            <v-col lg="6">
                                <v-text-field
                                    v-model="item.meta_value"
                                    type="text"
                                    label="Meta value"
                                    :rules="[v => !!v || 'Meta value is required']">
                                </v-text-field>
                            </v-col>
                            
                            <v-col sm="2" class="ml-auto text-right">
                                <span class="ic-remove clickable" @click="onRemove(item, index)">
                                    <i class="fa fa-times-circle fa-2x"></i>
                                </span>
                            </v-col>
                        </v-row>
                    </v-hover>
                </div>
            </v-container>
        </v-form>

        <div class="mt-3 d-flex">
            <v-spacer></v-spacer>
            <v-btn class="mr-1" small @click="onAddMore">Add more configurations</v-btn>
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
</template>

<script>
import { EventBus } from '../../event-bus.js'

export default {

    props: {
        integrationId: { type: Number, default: null },
        integrationMeta: { type: Array, default: [] }
    },

    data() {
        return {
            items: this.integrationMeta,
            form: null,
            isSubmit: false,
        }
    },

    methods: {
        onAddMore() {
            this.items.push({
                meta_key: '',
                meta_value: '',
                integration_id: this.integrationId
            })
        },


        onRemove(itemMeta) {
            let index = this.items.findIndex(item => item == itemMeta)
            this.items.splice(index, 1)
            if (Number.isInteger(itemMeta.id)) {
                this.$http
                    .delete('/api/integrationmetas/' + itemMeta.id)
                    .then(() => {
                        EventBus.$emit('show-success', 'Successfully deleted integration meta.');
                    })
                    .catch(err => {
                        EventBus.$emit('show-error', err)
                    })
            }
        },

        async onSubmit(e) {
            e.preventDefault();


            // validate
            if (this.items == null || this.items.length == 0) {
                return
            }

            this.isSubmit = true

            if (this.$refs.metaForm.validate()) {
                await this.$http
                    .post('/api/integrationmetas/bulk', this.items)
                    .then(({ data }) => {
                        EventBus.$emit('show-success', 'Successfully saved integrations.')
                        console.log('success bulk save: ', data)
                        this.prepareMapItems(data)
                    })
                    .catch(err => {
                        EventBus.$emit('show-error', err)
                    })

                this.isSubmit = false
            }
        },

        prepareMapItems(data) {
            if (
                data.data &&
                data.data.attributes &&
                data.data.attributes.metas
            ) {
                this.items = data.data.attributes.metas
            }
        }
    }
}

</script>