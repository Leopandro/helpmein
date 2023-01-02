<template>
    <RouterView/>
</template>

<script>
import {defineComponent, nextTick, onBeforeMount, onMounted} from "vue";
import {RouterView} from "vue-router";
import {useConfigStore} from "@/stores/config";
import {useThemeStore} from "@/stores/theme";
import {useBodyStore} from "@/stores/body";
import {themeMode} from "@/core/helpers/config";
import {initializeComponents} from "@/core/plugins/keenthemes";
import {useAuthStore} from "@/stores/auth";

export default defineComponent({
    name: "app",
    components: {
        RouterView,
    },
    errorCaptured(error, vm, info) {
        console.log(error,vm,info);
    },
    data() {
        return {
            router: ''
        }
    },
    mounted() {
        this.router = this.$router;
    },
    methods: {

        self() {
            console.log(this);
        },
    },
    setup() {
        const configStore = useConfigStore();
        const themeStore = useThemeStore();
        const bodyStore = useBodyStore();


        // verify auth token before each page change
        const authStore = useAuthStore();

        authStore.verifyAuth();

        onBeforeMount(() => {
            /**
             * Overrides the layout config using saved data from localStorage
             * remove this to use static config (@/core/config/DefaultLayoutConfig.ts)
             */
            configStore.overrideLayoutConfig();

            /**
             *  Sets a mode from configuration
             */
            themeStore.setThemeMode(themeMode.value);
        });

        onMounted(() => {
            nextTick(() => {
                initializeComponents();

                bodyStore.removeBodyClassName("page-loading");
            });
        });
    },
});
</script>

<style lang="scss">
@import "bootstrap-icons/font/bootstrap-icons.css";
@import "apexcharts/dist/apexcharts.css";
@import "quill/dist/quill.snow.css";
@import "animate.css";
@import "sweetalert2/dist/sweetalert2.css";
@import "nouislider/distribute/nouislider.css";
@import "@fortawesome/fontawesome-free/css/all.min.css";
@import "socicon/css/socicon.css";
@import "line-awesome/dist/line-awesome/css/line-awesome.css";
@import "dropzone/dist/dropzone.css";
@import "@vueform/multiselect/themes/default.css";
@import "prism-themes/themes/prism-shades-of-purple.css";
@import "element-plus/dist/index.css";

// Main demo style scss
@import "assets/fonticon/fonticon.css";
@import "assets/sass/element-ui.dark";
@import "assets/sass/plugins";
@import "assets/sass/style";

#app {
    display: contents;
}
.box {
    display: flex;
}
.form-check .form-check-label {
    cursor: pointer;
}
</style>
