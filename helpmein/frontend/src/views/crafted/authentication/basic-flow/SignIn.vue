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
    <SelectRoleModal ref="childComponent" :parent="this"></SelectRoleModal>
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
import SelectRoleModal from "@/components/modal/SelectRoleModal.vue";
import i18n from "@/core/plugins/i18n";
import {Modal} from "bootstrap";

export default defineComponent({
    name: "sign-in",
    components: {
        Field,
        VForm,
        ErrorMessage,
        SelectRoleModal
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
    mounted() {
    },
    methods: {
        loginAs(role) {
            const permissionStore = usePermissionStore();
            const store = useAuthStore();
            store.currentRole = role;
            this.$router.push({path: permissionStore.getUrlByRole(store.currentRole.id)});
        },
        async onSubmitLogin(values: any) {
            const store = useAuthStore();
            this.$refs.submitButton.disabled = true;
            this.$refs.submitButton.setAttribute("data-kt-indicator", "on");
            console.log(this.$refs.submitButton)
            values = values as User;
            // Clear existing errors
            store.logout();

            if (this.$refs.submitButton.value) {
                // eslint-disable-next-line
                this.$refs.submitButton.value!.disabled = true;
                // Activate indicator
                this.$refs.submitButton.value.setAttribute("data-kt-indicator", "on");
            }

            // Send login request
            await store.login(values);
            const message = store.messages;

            if (!message) {
                console.log(store.roles);
                if (Object.keys(store.roles).length > 1) {
                    this.$refs.childComponent.openModal(store.roles);
                } else {
                    this.loginAs(store.currentRole)
                }
                // Swal.fire({
                //     template: '#my-template'
                // })
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
            this.$refs.submitButton.disabled = false;
            this.$refs.submitButton.setAttribute("data-kt-indicator", "off");
        }
    },
    setup() {
        const store = useAuthStore();
        store.errors = "";
        //Create form validation object
        const login = Yup.object().shape({});
        return {
            login,
        };
    },
});
</script>
