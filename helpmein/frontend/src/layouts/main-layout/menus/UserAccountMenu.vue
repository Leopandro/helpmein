<template>
    <!--begin::Menu-->
    <div
        class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semobold py-4 fs-6 w-275px"
        data-kt-menu="true"
    >
        <!--begin::Menu item-->
        <div class="menu-item px-3">
            <div class="menu-content d-flex align-items-center px-3">
                <!--begin::Avatar-->
                <div class="symbol symbol-50px me-5">
                    <img alt="Logo" :src="avatar" style="width: 50px; height: 50px; object-fit: cover"/>
                </div>
                <!--end::Avatar-->

                <!--begin::Username-->
                <div class="d-flex flex-column">
                    <div class="fw-bold d-flex align-items-center fs-5">
                        {{ username }}
                    </div>
                    <a :href="'mailto:'+email" class="fw-semobold text-muted text-hover-primary fs-7"
                    >{{ email }}</a>
                </div>
                <!--end::Username-->
            </div>
        </div>
        <!--end::Menu item-->

        <!--begin::Menu separator-->
        <div class="separator my-2" style="display: none"></div>
        <!--end::Menu separator-->

        <!--begin::Menu item-->
        <div class="menu-item px-5" style="display: block">
            <router-link to="/profile" class="menu-link px-5">
                {{ $t('Профиль') }}
            </router-link>
        </div>
        <!--end::Menu item-->

        <!--begin::Menu item-->
        <!--end::Menu item-->


        <!--begin::Menu separator-->
        <div class="separator my-2"></div>
        <!--end::Menu separator-->

        <!--begin::Menu item-->
        <div
            class="menu-item px-5"
            data-kt-menu-trigger="hover"
            data-kt-menu-placement="left-start"
            data-kt-menu-flip="center, top"
        >
            <router-link to="/pages/profile/overview" class="menu-link px-5">
                <span class="menu-title position-relative">
                    {{ $t('Язык') }}
                    <span class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">
                        {{ $t(currentLanguageLocale.name) }}
                        <img class="w-15px h-15px rounded-1 ms-2"
                        :src="currentLanguageLocale.flag"
                        alt="metronic"/>
                    </span>
                </span>
            </router-link>

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-dropdown w-175px py-4">
                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <a
                        @click="setLang('ru')"
                        href="javascript:;"
                        class="menu-link d-flex px-5"
                        :class="{ active: currentLanguage === 'ru' }"
                    >
            <span class="symbol symbol-20px me-4">
              <img
                  class="rounded-1"
                  src="/media/flags/russia.svg"
                  alt="metronic"
              />
            </span>
                        Русский
                    </a>
                </div>

                <div class="menu-item px-3">
                    <a
                        @click="setLang('en')"
                        href="javascript:;"
                        class="menu-link d-flex px-5"
                        :class="{ active: currentLanguage === 'en' }"
                    >
            <span class="symbol symbol-20px me-4">
              <img
                  class="rounded-1"
                  src="/media/flags/united-states.svg"
                  alt="metronic"
              />
            </span>
                        English
                    </a>
                </div>
                <!--end::Menu item-->
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item--><!--begin::Menu item-->
        <div
            class="menu-item px-5"
            data-kt-menu-trigger="hover"
            data-kt-menu-placement="left-start"
            data-kt-menu-flip="center, top"
        >
            <router-link to="/pages/profile/overview" class="menu-link px-5">
                <span class="menu-title position-relative">
                    {{ $t('Роль') }}
                </span>
            </router-link>

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-dropdown w-175px py-4">
                <!--begin::Menu item-->
                <div class="menu-item px-3" v-for="role in roleList">
                    <a
                        @click="setRole(role)"
                        href="javascript:;"
                        class="menu-link d-flex px-5"
                        :class="{ active: currentRole === role }"
                    >
                        {{ $t(roleNameList[role]) }}
                    </a>
                </div>
                <!--end::Menu item-->
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->

        <!--begin::Menu item-->
        <div class="menu-item px-5">
            <a @click="signOut()" class="menu-link px-5"> {{ $t('Выход') }} </a>
        </div>
        <!--end::Menu item-->
    </div>
    <!--end::Menu-->
</template>

<script lang="ts">
import {defineComponent, computed} from "vue";
import {useI18n} from "vue-i18n";
import {useAuthStore} from "@/stores/auth";
import {useRouter} from "vue-router";
import {usePermissionStore} from "@/stores/permission";

export default defineComponent({
    name: "kt-user-menu",
    components: {},
    methods: {
        setRole(role: string) {
            const store = useAuthStore();
            const permission = usePermissionStore();
            store.setCurrentRole(role);
            let resolve = this.$router.resolve({
                path: permission.getUrlByRole(role)
            });
            window.location.href = "/#" + resolve.fullPath;
            window.location.reload();
        }
    },
    setup() {
        const roleNameList = {
            'Teacher': 'Преподаватель',
            'Client': 'Клиент',
        };
        const router = useRouter();
        const i18n2 = useI18n();
        const store = useAuthStore();
        const permission = usePermissionStore();

        const countries = {
            ru: {
                flag: "/media/flags/russia.svg",
                name: "Русский",
            },
            en: {
                flag: "/media/flags/united-states.svg",
                name: "Английский",
            },
        };

        const signOut = () => {
            store.logout();
            router.push({name: "sign-in"});
        };

        const setLang = (lang: string) => {
            localStorage.setItem("lang", lang);
            i18n2.locale.value = lang;
        };

        const roleList = computed(() => {
            return store.roles;
        })

        const currentLanguage = computed(() => {
            return i18n2.locale.value;
        });

        const currentLanguageLocale = computed(() => {
            return countries[i18n2.locale.value as keyof typeof countries];
        });
        const username = computed(() => {
            const store = useAuthStore();
            const user = store.user;
            return user.name + ' ' + user.surname;
        });
        const avatar = computed(() => {
            const store = useAuthStore();
            const avatar = store.avatar;
            return avatar;
        });
        const currentRole = computed(() => {
            const store = useAuthStore();
            return store.currentRole;
        });
        const email = computed(() => {
            const store = useAuthStore();
            const user = store.user;
            return user.email;
        });
        return {
            signOut,
            setLang,
            roleNameList,
            currentRole,
            roleList,
            avatar,
            username,
            email,
            currentLanguage,
            currentLanguageLocale,
            countries,
        };
    },
});
</script>
