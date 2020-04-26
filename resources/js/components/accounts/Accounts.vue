<template>
    <div>
        <h5 class="title mb-5">
            <i class="fas fa-cog"></i> Accounts
            <v-btn 
                v-if="isShowButton"
                :to="{ name: 'account add' }"
                small
                color="primary"
                class="float-right">
                Add Account
            </v-btn>
        </h5>
        <router-view></router-view>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isShowButton: false
        }
    },
    watch: {
        $route (to, from){
            if (to.name != from.name) {
                this.isShowButton = this.$route.name == 'account list'
            }
        }
    },
    created() {
        this.isShowButton = this.$route.name == 'account list'
    }
}
</script>