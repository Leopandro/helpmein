<template>
    <!--begin::Page title-->
    <div
        v-if="pageTitleDisplay"
        :class="`page-title d-flex flex-${pageTitleDirection} justify-content-center flex-wrap me-3`"
    >
        <template v-if="pageTitle">
            <h1
                v-if="typeof pageTitle === 'string'"
                class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0"
            >
                {{ $t(currentTitle) }}
            </h1>

            <span
                v-if="pageTitleDirection === 'row' && pageTitleBreadcrumbDisplay"
                class="h-20px border-gray-200 border-start mx-3"
            ></span>

            <!--begin::Breadcrumb-->
            <ul
                v-if="breadcrumbs && pageTitleBreadcrumbDisplay"
                class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1"
            >
                <!--begin::Item-->
                <li class="breadcrumb-item text-muted">
                    <router-link to="/" class="text-muted text-hover-primary"
                    >
                        {{ $t('Главная') }}
                    </router-link
                    >
                </li>
                <!--end::Item-->
                <template v-for="(item, i) in breadcrumbs" :key="i">
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li v-if="item.path" class="breadcrumb-item text-muted">
                        <router-link :to="item.path" class="router-link-active text-muted text-hover-primary">{{ $t(item.name) }} </router-link>
                    </li>
                    <li v-else class="breadcrumb-item text-muted">{{ $t(item) }}</li>
                    <!--end::Item-->
                </template>
            </ul>
            <!--end::Breadcrumb-->
        </template>
    </div>
    <div v-else class="align-items-stretch"></div>
    <!--end::Page title-->
</template>

<script lang="ts">
import {defineComponent, computed, ref} from "vue";
import {
    pageTitleDisplay,
    pageTitleBreadcrumbDisplay,
    pageTitleDirection,
} from "@/core/helpers/config";
import {useRoute} from "vue-router";
import {useRouterStore} from "@/stores/router";

export default defineComponent({
    name: "layout-page-title",
    components: {},
    setup() {
        const route = useRoute();
        const router = useRouterStore();

        let pageTitle = computed(() => {
            return route.meta.pageTitle;
        });
        router.currentTitle = route.meta.pageTitle;
        const currentTitle = computed(() => {
            const store = useRouterStore();
            return store.currentTitle;
        });
        let breadcrumbs = computed(() => {
            return route.meta.breadcrumbs;
        });

        return {
            currentTitle,
            pageTitle,
            breadcrumbs,
            pageTitleDisplay,
            pageTitleBreadcrumbDisplay,
            pageTitleDirection,
        };
    },
});
</script>
