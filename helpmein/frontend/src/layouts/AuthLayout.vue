<template>
    <!--begin::Authentication Layout -->
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <!--begin::Body-->
        <div
            class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1"
        >
            <!--begin::Form-->
            <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                <!--begin::Wrapper-->
                <div>
                    <router-view></router-view>
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Form-->

            <!--begin::Footer-->
            <div class="d-flex flex-center flex-wrap px-5">
                <!--begin::Links-->
                <div class="d-flex fw-semibold text-primary fs-base">
                    <a href="mailto: admin@help-me-in.ru" class="px-5"
                       target="_blank">{{ $t('Есть вопросы? Свяжитесь с нами') }}</a>
                </div>
                <!--end::Links-->
            </div>
            <!--end::Footer-->
        </div>
        <!--end::Body-->

    </div>
    <!--end::Authentication Layout -->
</template>

<script lang="ts">
import {defineComponent, onMounted} from "vue";
import LayoutService from "@/core/services/LayoutService";
import {useBodyStore} from "@/stores/body";
import {useI18n} from "vue-i18n";

export default defineComponent({
    name: "auth-layout",
    components: {},
    setup() {
        const store = useBodyStore();
        const i18n2 = useI18n();
        i18n2.locale.value = localStorage.getItem("lang")
            ? (localStorage.getItem("lang") as string)
            : "en";
        console.log(i18n2)
        onMounted(() => {
            LayoutService.emptyElementClassesAndAttributes(document.body);

            store.addBodyClassname("app-blank");
            store.addBodyClassname("bg-body");
        });
    },
});
</script>
