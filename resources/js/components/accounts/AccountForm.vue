<template>
    <v-form
        ref="form"
        v-model="valid"
        class="p-3 pt-0"
        @submit="onSubmit">

        <!-- client name -->
        <v-text-field
            v-model="form.client_name"
            :error-messages="clientNameErrors"
            :counter="30"
            label="Client Name"
            required
            @input="$v.form.client_name.$touch()"
            @blur="$v.form.client_name.$touch()">
        </v-text-field>

        <!-- client notes -->
        <v-textarea
            outlined
            label="Client Notes"
            v-model="form.client_notes"
            class="mt-5">
        </v-textarea>

        <!-- client user -->
        <v-select
            v-model="form.user_id"
            :items="users"
            :error-messages="userIdErrors"
            :rules="[v => !!v || 'User is required']"
            label="Choose User"
            required>
        </v-select>

        <div class="mt-3">
            <v-btn type="submit" small class="mr-1" color="success">{{ submitText }}</v-btn>
            <v-btn small @click="clear">clear</v-btn>
        </div>
    </v-form>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required, maxLength, numeric } from 'vuelidate/lib/validators'
import { EventBus } from '../../event-bus.js'

export default {
    mixins: [validationMixin],
    validations: {
        form: {
            client_name: { required, maxLength: maxLength(30) },
            user_id: { required, numeric }
        }
    },

    props: {
        submitText:  { type: String, default: 'Add' },
        submit:      { type: Function, default: () => {} },
        clientName:  { type: String, default: '' },
        clientNotes: { type: String, default: '' },
        userId:      { type: Number, default: 0 }
    },

    data() {
        return {
            valid: false,

            users: [],
            form: {
                client_name: this.clientName,
                client_notes: this.clientNotes,
                user_id: this.userId
            }
        }
    },

    created() {
        // we need to fetch all users 
        this.$http
            .get('/api/users')
            .then(({ data }) => {
                console.log('users : ', data)
                this.users = data.data.map(user => {
                    return { value: parseInt(user.id), text: user.attributes.name }
                })
            })
            .catch(err => {
                EventBus.$emit('show-error', 'Failed to fetch users.');
            })
    },

    computed: {
        clientNameErrors() {
            const errors = []
            if (!this.$v.form.client_name.$dirty) return errors
            !this.$v.form.client_name.maxLength && errors.push('Client name must be at most 30 characters long')
            !this.$v.form.client_name.required && errors.push('Client name is required.')
            return errors
        },
        userIdErrors() {
            const errors = []
            if (!this.$v.form.user_id.$dirty) return errors
            !this.$v.form.user_id.required && errors.push('Bind to user is required.')
            return errors
        }
    },

    methods: {
        onSubmit(e) {
            this.$v.form.$touch();
            e.preventDefault();

            if (this.valid) {
                // call callback submit
                this.submit(this.form)
            } else {
                EventBus.$emit('show-error', 'Failed! Please try again.');
            }
        },
        clear() {
            this.$refs.form.reset()
        }
    }
}
</script>