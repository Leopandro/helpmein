<template>
    <!--begin::Wrapper-->
    <div class="w-lg-500px p-10">
        <!--begin::Form-->
        <VForm
            class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework"
            @submit="onSubmitForgotPassword"
            id="kt_login_password_reset_form"
        >
            <!--begin::Heading-->
            <div class="text-center mb-10">
                <!--begin::Title-->
                <h1 class="text-dark mb-3">{{ $t('Забыли пароль ?') }}</h1>
                <!--end::Title-->

                <!--begin::Link-->
                <div class="text-gray-400 fw-semobold fs-4">
                    {{ $t('Введите e-mail, чтобы получить ссылку для восстановления пароля') }}
                </div>
                <!--end::Link-->
            </div>
            <!--begin::Heading-->

            <!--begin::Input group-->
            <div class="fv-row mb-10">
                <label class="form-label fw-bold text-gray-900 fs-6">{{ $t('E-mail') }}</label>
                <Field
                    class="form-control form-control-solid"
                    type="email"
                    placeholder=""
                    name="email"
                    v-model="object.email"
                    autocomplete="off"
                />
                <div class="fv-plugins-message-container" v-if="errors.email">
                    <div class="fv-help-block">
                        {{ errors.email[0] }}
                    </div>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Actions-->
            <div class="justify-content-center row between-sm">
                <div class="col-auto p-1">
                    <button
                        type="submit"
                        ref="submitButton"
                        id="kt_password_reset_submit"
                        class="btn btn-lg btn-primary"
                    >
                        <span class="indicator-label"> {{ $t('Подтвердить') }} </span>
                        <span class="indicator-progress">
            {{ $t('Пожалуйста подождите...') }}
            <span
                class="spinner-border spinner-border-sm align-middle ms-2"
            ></span>
          </span>
                    </button>
                </div>
                <div class="col-auto p-1">
                    <router-link to="/sign-in" class="btn btn-lg btn-light-primary fw-bold">{{ $t('Отмена') }}
                    </router-link>
                </div>

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
import {useAuthStore} from "@/stores/auth";
import * as Yup from "yup";
import Swal from "sweetalert2";
import {useRouter} from "vue-router";
import {useLoginStore} from "@/stores/login";

export default defineComponent({
    name: "password-reset",
    components: {
        Field,
        VForm,
        ErrorMessage,
    },
    data() {
        return {
            object: {
                email: ''
            }
        }
    },
    computed: {
        errors() {
            const store = useAuthStore();
            let errors = store.errors;
            return errors;
        }
    },
    mounted() {
        let store = useLoginStore();
        console.log(store.email);
        this.object.email = store.email;
    },
    setup() {
        const store = useAuthStore();
        store.errors = "";
        const router = useRouter();
        const errors = store.errors;
        const submitButton = ref<HTMLButtonElement | null>(null);


        //Form submit function
        const onSubmitForgotPassword = async (values: any) => {
            values = values as string;

            // eslint-disable-next-line
            submitButton.value!.disabled = true;
            // Activate loading indicator
            submitButton.value?.setAttribute("data-kt-indicator", "on");

            // dummy delay
            // Send login request
            await store.forgotPassword(values);

            const error = store.errors;
            const message = store.messages;
            if (!error) {
                Swal.fire({
                    text: message,
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ок!",
                    heightAuto: false,
                    customClass: {
                        confirmButton: "btn fw-semobold btn-light-primary",
                    },
                }).then(() => {
                    // Go to page after successfully login
                    router.push({name: "sign-in"});
                });
                ;
            } else {
                Swal.fire({
                    text: message,
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ок!",
                    heightAuto: false,
                    customClass: {
                        confirmButton: "btn fw-semobold btn-light-danger",
                    },
                });
            }

            submitButton.value?.removeAttribute("data-kt-indicator");
            // eslint-disable-next-line
            submitButton.value!.disabled = false;
        };

        return {
            onSubmitForgotPassword,
            submitButton,
        };
    },
});
</script>
