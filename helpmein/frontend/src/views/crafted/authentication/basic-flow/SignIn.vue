<template>
    <!--begin::Wrapper-->
    <div class="w-lg-500px p-10">
        <!--begin::Form-->
        <VForm
            class="form w-100"
            id="kt_login_signin_form"
            @submit="onSubmitLogin"
            :validation-schema="login"
            :initial-values="{ email: '', password: '' }"
        >
            <!--begin::Heading-->
            <div class="text-center mb-10">
                <!--begin::Title-->
                <h1 class="text-dark mb-3">{{ $t('Войти') }}</h1>
                <!--end::Title-->

                <!--begin::Link-->
                <div class="text-gray-400 fw-semobold fs-4">
                    {{ $t('Не зарегистрированы?') }}

                    <router-link to="/sign-up" class="link-primary fw-bold">
                        {{ $t('Создать аккаунт') }}
                    </router-link>
                </div>
                <!--end::Link-->
            </div>
            <!--begin::Heading-->

            <!--begin::Input group-->
            <div class="fv-row mb-10">
                <!--begin::Label-->
                <label class="form-label fs-6 fw-bold text-dark">{{ $t('Почта') }}</label>
                <!--end::Label-->

                <!--begin::Input-->
                <Field
                    tabindex="1"
                    class="form-control form-control-lg form-control-solid"
                    type="text"
                    name="email"
                    v-model="object.email"
                    autocomplete="off"
                />
                <div class="fv-plugins-message-container" v-if="errors.email">
                    <div class="fv-help-block">
                        {{ errors.email[0] }}
                    </div>
                </div>
                <!--end::Input-->
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="fv-row mb-10">
                <!--begin::Wrapper-->
                <div class="d-flex flex-stack mb-2">
                    <!--begin::Label-->
                    <label class="form-label fw-bold text-dark fs-6 mb-0">{{ $t('Пароль') }}</label>
                    <!--end::Label-->

                    <!--begin::Link-->
                    <router-link to="/password-reset" v-on:click="forgotPassword($e)" class="link-primary fs-6 fw-bold">
                        {{ $t('Забыли пароль ?') }}
                    </router-link>
                    <!--end::Link-->
                </div>
                <!--end::Wrapper-->

                <!--begin::Input-->
                <Field
                    tabindex="2"
                    class="form-control form-control-lg form-control-solid"
                    type="password"
                    v-model="object.password"
                    name="password"
                    autocomplete="off"
                />
                <div class="fv-plugins-message-container" v-if="errors.password">
                    <div class="fv-help-block">
                        {{ errors.password[0] }}
                    </div>
                </div>
                <!--end::Input-->
            </div>
            <!--end::Input group-->

            <!--begin::Actions-->
            <div class="text-center">
                <!--begin::Submit button-->
                <button
                    tabindex="3"
                    type="submit"
                    ref="submitButton"
                    id="kt_sign_in_submit"
                    class="btn btn-lg btn-primary w-100 mb-5"
                >
                    <span class="indicator-label"> {{ $t('Продолжить') }} </span>

                    <span class="indicator-progress">
            {{ $t('Подождите ...') }}
            <span
                class="spinner-border spinner-border-sm align-middle ms-2"
            ></span>
          </span>
                </button>
                <!--end::Submit button-->

            </div>
            <!--end::Actions-->
        </VForm>
        <!--end::Form-->
    </div>
    <!--end::Wrapper-->
</template>

<script lang="ts">
import {defineComponent, ref} from "vue";
import {ErrorMessage, Field, Form as VForm} from "vee-validate";
import {useAuthStore, type User} from "@/stores/auth";
import {useRouter} from "vue-router";
import Swal from "sweetalert2";
import * as Yup from "yup";
import {usePermissionStore} from "@/stores/permission";
import {useLoginStore} from "@/stores/login";
import i18n from "@/core/plugins/i18n";

export default defineComponent({
    name: "sign-in",
    components: {
        Field,
        VForm,
        ErrorMessage,
    },
    computed: {
        errors() {
            const store = useAuthStore();
            let errors = store.errors;
            return errors;
        }
    },
    data() {
        return {
            name: 123,
            object: {
                email: '',
                password: ''
            }
        };
    },
    unmounted() {
        let store = useLoginStore();
        store.email = this.object.email;
    },
    methods: {
        forgotPassword() {
        }
    },
    setup() {
        console.log(i18n.global.locale);
        const store = useAuthStore();
        store.errors = "";
        const router = useRouter();
        const permissionStore = usePermissionStore();
        const submitButton = ref<HTMLButtonElement | null>(null);
        //Create form validation object
        const login = Yup.object().shape({});

        //Form submit function
        const onSubmitLogin = async (values: any) => {
            values = values as User;
            // Clear existing errors
            store.logout();

            if (submitButton.value) {
                // eslint-disable-next-line
                submitButton.value!.disabled = true;
                // Activate indicator
                submitButton.value.setAttribute("data-kt-indicator", "on");
            }

            // Send login request
            await store.login(values);
            const message = store.messages;
            const permissions = store.permissions;
            const error = store.errors;
            console.log(message);
            if (!message) {
                Swal.fire({
                    text: i18n.global.t("Вы успешно авторизовались!"),
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ок!",
                    heightAuto: false,
                    customClass: {
                        confirmButton: "btn fw-semobold btn-light-primary",
                    },
                }).then(() => {
                    router.push({path: permissionStore.getUrlByRole(store.roles)});
                });
            } else {
                Swal.fire({
                    text: message,
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: i18n.global.t("Ок!"),
                    heightAuto: false,
                    customClass: {
                        confirmButton: "btn fw-semobold btn-light-danger",
                    },
                }).then(() => {
                    let errors = {};
                });
            }

            //Deactivate indicator
            submitButton.value?.removeAttribute("data-kt-indicator");
            // eslint-disable-next-line
            submitButton.value!.disabled = false;
        };

        return {
            onSubmitLogin,
            login,
            submitButton,
        };
    },
});
</script>
