<template>
    <v-dialog v-model="isShow" persistent :max-width="width">
        <v-card>
            <v-card-title class="headline">{{ title }}</v-card-title>
            <v-card-text>{{ message }}</v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn v-if="!isOkayOnly" color="default darken-1" text @click="isShow = false">{{ cancelText }}</v-btn>
                <v-btn :color="color + ' darken-1'" text @click="onOk">{{ okText }}</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>


export default {

    props: {
        width: { type: String, default: '290' },
        color: { type: String, default: 'light' },
        okText: { type: String, default: 'Ok' },
        cancelText: { type: String, default: 'Cancel' },
        isOkayOnly: { type: Boolean, default: false }
    },

    data() {
        return {
            isShow: false,
            title: '',
            message: '',
            callback: () => {}
        }
    },

    methods: {
        // this function accepts title and message of the modal to show
        // cb is the callback function to execute after ok button clicked
        onShow(title, message, cb) {
            this.isShow   = true
            this.title    = title
            this.message  = message
            this.callback = cb
        },

        // on ok execute
        onOk() {
            this.isShow = false
            if (typeof this.callback == 'function') {
                this.callback()
            }
        }
    }

}
</script>