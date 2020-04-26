<template>

    <v-row>
        <v-col md="6">
            <v-card color="light" padding>
                <v-card-title class="headline">Add New Account</v-card-title>
                <account-form :submit="onAdd"></account-form>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
import AccountForm from './AccountForm'
import { EventBus } from '../../event-bus.js'

export default {
    components: {
        AccountForm
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
                .post('/api/accounts', data)
                .then(res => {
                    // show info
                    EventBus.$emit('show-success', 'Successfully saved account.');
                    // console.log('res is : ', res)
                    this.$router.replace({ name: 'account list'});
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