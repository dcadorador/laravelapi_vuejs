<template>
    <v-card
        color="light"
        padding>
        <v-card-title class="headline">Edit User</v-card-title>
        <user-form v-if="uForm" :dataForm="uForm" :submit="onEdit" submitText="Update"></user-form>
        <v-progress-linear
            v-else
            indeterminate
            color="primary"
            class="mb-5"
        ></v-progress-linear>
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
            uForm: null,
            id: null,
            isSubmit: false
        }
    },

    watch: {
        $route (to, from){
            console.log('to : ', to)
            console.log('from : ', from)
            if (to.params.id != from.params.id) {
                this.id = to.params.id
                this.uForm = null
                this.onFetchForm(this.id)
            }
        }
    },


    created() {
        this.id = this.$route.params.id
        this.onFetchForm(this.id)
    },

    methods: {
        onFetchForm(id) {
            this.$http
                .get('/api/users/' + id)
                .then(({ data }) => {
                    this.uForm = data.data.attributes
                })
                .catch(err => {
                    EventBus.$emit('show-error', err)
                })
        },

        onEdit(data) {
            if (this.isSubmit) {
                return
            }

            this.isSubmit = true
            this.$http
                .put('/api/users/' + this.id, data)
                .then(res => {
                    // show info
                    EventBus.$emit('show-success', 'Successfully updated the user.');
                    this.$router.replace({ name: 'user list' });
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