<template>
    <!--begin::Wrapper-->
    <div class="w-lg-500px p-10">
        <!--begin::Form-->
        <VForm
            class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework"
            @submit="onSubmitForgotPassword"
            id="kt_login_password_reset_form"
            :validation-schema="forgotPassword"
        >
            <!--begin::Heading-->
            <div class="text-center mb-10">
                <!--begin::Title-->
                <h1 class="text-dark mb-3">{{$t('Установить пароль')}}</h1>
                <!--end::Title-->

                <!--begin::Link-->
                <div class="text-gray-400 fw-semobold fs-4">
                    {{$t('Пароль должен быть не менее 4 символов, желательно содержать символы и цифры')}}
                </div>
                <!--end::Link-->
            </div>
            <!--begin::Heading-->

            <!--begin::Input group-->
            <div class="mb-10">
                <label class="form-label">{{$t('Пароль')}}</label>
                <input v-model="model.password" type="password" class="form-control" placeholder="">
                <div v-if="errors.password" class="fv-plugins-message-container invalid-feedback">
                    <div data-field="daterangepicker_input" data-validator="notEmpty">{{ errors.password[0] }}</div>
                </div>
            </div>
            <div class="mb-10">
                <label class="form-label">{{$t('Пароль(повторно)')}}</label>
                <input v-model="model.password_confirm" type="password" class="form-control" placeholder="">
                <div v-if="errors.password_confirm" class="fv-plugins-message-container invalid-feedback">
                    <div data-field="daterangepicker_input" data-validator="notEmpty">{{
                            errors.password_confirm[0]
                        }}
                    </div>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Actions-->
            <div class="justify-content-center row between-sm">
                <div class="col-auto p-1">
                    <button
                        type="submit"
                        v-on:click="submitButton()"
                        id="kt_password_reset_submit"
                        class="btn btn-lg btn-primary fw-bold me-4"
                    >
                        <span class="indicator-label"> {{$t('Подтвердить')}} </span>
                        <span class="indicator-progress">
            {{$t('Пожалуйста подождите...')}}
            <span
                class="spinner-border spinner-border-sm align-middle ms-2"
            ></span>
          </span>
                    </button>
                </div>
                <div class="col-auto p-1">
                    <router-link to="/sign-in" class="btn btn-lg btn-light-primary fw-bold"
                    >{{$t('Отмена')}}
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

export default defineComponent({
    name: "password-update",
    components: {
        Field,
        VForm,
        ErrorMessage,
    },
    data() {
        return {
            model: {
                password: '',
                password_confirm: ''
            }
        }
    },
    methods: {
        async submitButton() {
            const store = useAuthStore();
            const router = useRouter();
            let object = {
                token: this.$route.query.token,
                ...this.model,
            };
            await store.updatePassword(object);
            const error = store.errors;
            const message = store.messages;
            if (!message) {
                Swal.fire({
                    text: "Пароль успешно изменен",
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ок!",
                    heightAuto: false,
                    customClass: {
                        confirmButton: "btn fw-semobold btn-light-primary",
                    },
                }).then(() => {
                    // Go to page after successfully login
                    this.$router.push({name: "sign-in"});
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
        }
    },
    computed: {
        errors() {
            const store = useAuthStore();
            let errors = store.errors;
            return errors;
        }
    },
    setup() {
        const store = useAuthStore();

        const submitButton = ref<HTMLButtonElement | null>(null);

        //Create form validation object
        const forgotPassword = Yup.object().shape({
            email: Yup.string().email().required().label("Email"),
        });

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


            submitButton.value?.removeAttribute("data-kt-indicator");
            // eslint-disable-next-line
            submitButton.value!.disabled = false;
        };

        return {
            onSubmitForgotPassword,
            forgotPassword,
        };
    },
});
</script>
