<template>
    <v-form
        ref="form"
        v-model="valid"
        class="p-3 pt-0"
        @submit="onSubmit"
        >
        <v-text-field
          v-model="form.name"
          :error-messages="nameErrors"
          :counter="30"
          label="Name"
          required
          @input="$v.form.name.$touch()"
          @blur="$v.form.name.$touch()">
        </v-text-field>
        <v-text-field
            v-model="form.email"
            :error-messages="emailErrors"
            label="E-mail"
            required
            @input="$v.form.email.$touch()"
            @blur="$v.form.email.$touch()">
        </v-text-field>

        <v-select
            v-model="form.roles"
            :items="roles"
            :error-messages="rolesErrors"
            :rules="[v => !!v || 'Role is required']"
            label="Role">
        </v-select>

        <v-text-field
            v-model="form.password"
            type="password"
            name="password"
            label="Password"
            hint="At least 8 characters"
            counter
            @input="$v.form.password.$touch()"
            @blur="$v.form.password.$touch()"
            :error-messages="passwordErrors">
        </v-text-field>

        <v-text-field
            v-model="form.passwordConfirm"
            type="password"
            name="password"
            label="Password Confirmation"
            hint="At least 8 characters"
            counter
            @input="$v.form.passwordConfirm.$touch()"
            @blur="$v.form.passwordConfirm.$touch()"
            :error-messages="passwordConfirmationErrors">
        </v-text-field>

        <div class="mt-3">
            <v-btn type="submit" small class="mr-4" color="success">{{ submitText }}</v-btn>
            <v-btn small @click="clear">clear</v-btn>
            <v-btn small class="float-right" :to="{ name: 'user list' }">Close</v-btn>
        </div>

    </v-form>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required, maxLength, minLength, email } from 'vuelidate/lib/validators'
import { EventBus } from '../../event-bus.js'

export default {
    mixins: [validationMixin],
    validations: {
        form: {
            name: { required, maxLength: maxLength(30) },
            email: { required, email },
            password: { required, minLength: minLength(8) },
            passwordConfirm: { required, minLength: minLength(8) },
            roles: { required }
        }
    },

    props: {
        dataForm: {
            type: Object,
            default: () => {
                return {
                    valid: false,
                    name: '',
                    email: '',
                    roles: '',
                    password: '',
                    passwordConfirm: '',
                }
            }
        },
        submitText: { type: String, default: 'Save' },
        submit: { type: Function, default: (data) => {} },
    },

    data() {
        return {
            valid: false,
            roles: [
                { value: 'Super Admin', text: 'Super Admin' },
                { value: 'Admin', text: 'Admin' },
                { value: 'User', text: 'User' }
            ],

            form: {
                name: '',
                email: '',
                roles: '',
                password: '',
                passwordConfirm: '',
            }

        }
    },

    created() {

        // bind with props
        this.form.name = this.dataForm.name
        this.form.email = this.dataForm.email
        this.form.roles = this.dataForm.roles.length > 0 ? this.dataForm.roles[0] : null
        this.form.password = this.dataForm.password
        this.form.passwordConfirm = this.dataForm.passwordConfirm
    },


    computed: {

        nameErrors () {
            const errors = []
            if (!this.$v.form.name.$dirty) return errors
            !this.$v.form.name.maxLength && errors.push('Name must be at most 30 characters long')
            !this.$v.form.name.required && errors.push('Name is required.')
            return errors
        },
        emailErrors () {
            const errors = []
            if (!this.$v.form.email.$dirty) return errors
            !this.$v.form.email.email && errors.push('Must be valid email')
            !this.$v.form.email.required && errors.push('Email is required')
            return errors
        },
        rolesErrors() {
            const errors = []
            if (!this.$v.form.roles.$dirty) return errors
            !this.$v.form.roles.required && errors.push('Role is required')
            return errors
        },
        passwordErrors() {
            const errors = []
            if (!this.$v.form.password.$dirty) return errors
            !this.$v.form.password.minLength && errors.push('Password must be atleast 8 characters long')
            !this.$v.form.password.required && errors.push('Password is required.')
            return errors
        },
        passwordConfirmationErrors() {
            const errors = []
            if (!this.$v.form.passwordConfirm.$dirty) return errors
            !this.$v.form.passwordConfirm.minLength && errors.push('Password confirmation must be atleast 8 characters long')
            !this.$v.form.passwordConfirm.required && errors.push('Password confirmation is required.')
            this.form.password != this.form.passwordConfirm && errors.push('Password confirmation does not match.')
            return errors
        }
    },

    methods: {
        onSubmit(e) {
            e.preventDefault()
            this.$v.form.$touch()

            setTimeout(function() {
                if (this.valid) {

                    // submit to UserAdd or UserEdit
                    this.submit(this.form)
                } else {
                    EventBus.$emit('show-error', 'Failed! Please try again.');
                }
            }.bind(this), 100)
        },

        clear() {
            this.$refs.form.reset()
        }
    }
}
</script>