<template>
    <div class="mt-3 p-3">
        <h5 class="subtitle-2">
            Machship key
        </h5>
        <integrations-key-form
            ref="machshipKeyForm"
            :formItems="formItems"
            :remove="onRemove">
        </integrations-key-form>
        <div class="mt-3 pb-3 pl-3 d-flex">
            <v-spacer></v-spacer>
            <v-btn small color="primary" :disabled="isSubmit" @click="onSubmit">
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
import IntegrationsKeyForm from './IntegrationsKeyForm.vue'
// import moment from 'moment'

export default {
    props: {
        integrationId: { type: Number, default: null },
        integrationKeys: { type: Array, default: null }
    },

    components: {
        IntegrationsKeyForm
    },
    
    data() {
        console.log('testing this : ', this.integrationKeys)
        return {
            // machship key form
            formItems: null,
            isSubmit: false
        }
    },

    created() {
        let index = this.integrationKeys.findIndex(key => key.key_type === 'machship_token')
        if (index >= 0) {
            this.formItems = this.integrationKeys
        } else {
            this.formItems = [{
                integration_id: this.integrationId,
                key_type: 'machship_token',
                key_data: '',
                expiry: '',
                showDate: false,
            }]
        }
    },


    methods: {
        onRemove() {
            alert("on remove machship token")
        },

        async onSubmit() {
            this.isSubmit = true
            await this.$refs.machshipKeyForm.onKeyFormSubmit()
            this.isSubmit = false
        }
    }
}
</script>