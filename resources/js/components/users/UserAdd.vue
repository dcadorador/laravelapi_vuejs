<template>
    <v-card
        color="light"
        padding>
        <v-card-title class="headline">Add New User</v-card-title>
        <user-form submitText="Save" :submit="onSubmit"></user-form>
    </v-card>
</template>

<script>

import UserForm from './UserForm.vue'
import { EventBus } from '../../event-bus.js'

export default {
    components: {
        UserForm
    },
    data() {
        return {
            isSubmit: false
        }
    },

    methods: {
        onSubmit(data) {
            if (this.isSubmit) {
                return
            }

            console.log('data to submit: ', data)

            this.isSubmit = true
            this.$http
                .post('/api/users', data)
                .then(res => {
                    // show info
                    EventBus.$emit('show-success', 'Successfully inserted a new user.');
                    // console.log('res is : ', res)
                    this.$router.replace({ name: 'user list'});
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