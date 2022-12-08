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
        <h1 class="text-dark mb-3">Установить пароль</h1>
        <!--end::Title-->

        <!--begin::Link-->
        <div class="text-gray-400 fw-semobold fs-4">
          Введите пароль чтобы установить его
        </div>
        <!--end::Link-->
      </div>
      <!--begin::Heading-->

      <!--begin::Input group-->
        <div class="mb-10">
            <label class="form-label">Пароль</label>
            <input v-model="model.password" type="text" class="form-control" placeholder="">
            <div v-if="errors.password" class="fv-plugins-message-container invalid-feedback">
                <div data-field="daterangepicker_input" data-validator="notEmpty">{{ errors.password[0] }}</div>
            </div>
        </div>
        <div class="mb-10">
            <label class="form-label">Пароль(повторно)</label>
            <input v-model="model.password_confirm" type="text" class="form-control" placeholder="">
            <div v-if="errors.password_confirm" class="fv-plugins-message-container invalid-feedback">
                <div data-field="daterangepicker_input" data-validator="notEmpty">{{ errors.password_confirm[0] }}</div>
            </div>
        </div>
      <!--end::Input group-->

      <!--begin::Actions-->
      <div class="d-flex flex-wrap justify-content-center pb-lg-0">
        <button
          type="submit"
          v-on:click="submitButton()"
          id="kt_password_reset_submit"
          class="btn btn-lg btn-primary fw-bold me-4"
        >
          <span class="indicator-label"> Подтвердить </span>
          <span class="indicator-progress">
            Please wait...
            <span
              class="spinner-border spinner-border-sm align-middle ms-2"
            ></span>
          </span>
        </button>

        <router-link to="/sign-in" class="btn btn-lg btn-light-primary fw-bold"
          >Отмена</router-link
        >
      </div>
      <!--end::Actions-->
    </VForm>
    <!--end::Form-->
  </div>
  <!--end::Wrapper-->
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import { ErrorMessage, Field, Form as VForm } from "vee-validate";
import { useAuthStore } from "@/stores/auth";
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
            errors: {

            },
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
          this.errors = error;
          console.log(error)
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
                  router.push({ name: "sign-in" });
              });;
          } else {
              Swal.fire({
                  text: message,
                  icon: "error",
                  buttonsStyling: false,
                  confirmButtonText: "Try again!",
                  heightAuto: false,
                  customClass: {
                      confirmButton: "btn fw-semobold btn-light-danger",
                  },
              });
          }
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

      const error = store.errors;



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
