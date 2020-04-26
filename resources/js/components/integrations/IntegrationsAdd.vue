<template>
    <v-card color="light" padding>
        <v-card-title>Add New Integration</v-card-title>
        <v-row>
            <v-col>
                <integrations-form :submit="onAdd"></integrations-form>
            </v-col>
        </v-row>
    </v-card>
</template>

<script>
import IntegrationsForm from './IntegrationsForm.vue'
import { EventBus } from '../../event-bus.js'

export default {
    components: {
        IntegrationsForm
    },

    data() {
        return {
            isSubmit: false
        }
    },

    methods: {
        onAdd(data) {
            if (this.isSubmit) {
                return
            }

            this.isSubmit = true
            this.$http
                .post('/api/integrations', data)
                .then(res => {
                    // show info
                    EventBus.$emit('show-success', 'Successfully added new integration.');
                    // console.log('res is : ', res)
                    this.$router.replace({ name: 'integration list'});
                    this.isSubmit = false
                })
                .catch(err => {
                    EventBus.$emit('show-error', err);
                    this.isSubmit = false
                })
        }
    }
}
</script>