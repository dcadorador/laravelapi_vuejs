<template>
    <div>
        <h5 class="fusedship-title title mb-5">
            Integrations
            <v-btn class="float-right" v-if="isShowButton" :to="{ name: 'integration add' }" small color="primary">Add Integrations</v-btn>
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
                this.isShowButton = this.$route.name == 'integration list'
            }
        }
    },
    created() {
        this.isShowButton = this.$route.name == 'integration list'
    }
}
</script>