<template>
    <v-card class="mt-3" color="light">
        <v-card-title class="subtitle-2">
            Other Integration keys <v-spacer></v-spacer><i class="fas fa-key"></i>
        </v-card-title>

        <div class="p-3">
            <integrations-key-form
                ref="form"
                :integrationId="integrationId"
                :formItems="integrationKeys"
                :isMultiple="true">
            </integrations-key-form>
            <div class="mt-3">
                <v-btn small color="success" :disabled="isSubmit" @click="onSubmit">
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
                <v-btn small color="primary" @click="onAddMore">Add more keys</v-btn>
            </div>
        </div>
    </v-card>
</template>

<script>
import IntegrationsKeyForm from './IntegrationsKeyForm.vue'
import moment from 'moment'

export default {
    props: {
        integrationId: { type: Number, default: null },
        integrationKeys: { type: Array, default: null }
    },

    components: {
        IntegrationsKeyForm
    },
    
    data() {
        return {
            formItems: null,
            isSubmit: false
        }
    },

    methods: {

        async onSubmit(data) {
            this.isSubmit = true
            await this.$refs.form.onKeyFormSubmit()
            this.isSubmit = false
        },

        onAddMore() {
            this.$refs.form.onKeyFormAddMore();
        }
    }
}
</script>