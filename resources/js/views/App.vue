 <template>
    <div id="app">
        <component :is="layout">
            <router-view></router-view>
        </component>
    </div>
</template>
<script>
const default_layout = "default-layout";

export default {
    computed: {
        layout() {
            return (this.$route.meta.layout || default_layout);
        }
    }
};
</script>
