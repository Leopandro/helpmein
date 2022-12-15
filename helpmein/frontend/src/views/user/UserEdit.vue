<template>
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                Редактировать клиента
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label fw-semobold fs-6">Аватар</label>
                <div class="col-lg-8">
                    <div class="image-input image-input-outline" data-kt-image-input="true"
                         style="background-image: url(&quot;/metronic8/vue/demo1//media/avatars/blank.png&quot;);">
                        <div class="image-input-wrapper w-125px h-125px"
                             :style="{'background-image': model.avatar ? model.avatar : 'url(&quot;/media/avatars/blank.png&quot;)'}"></div>
                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"

                               data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar"><i
                            class="bi bi-pencil-fill fs-7"></i>
                            <input type="file" @change="handleImage" name="avatar" accept=".png, .jpg, .jpeg">
                            <input type="hidden" name="avatar_remove">
                        </label>
                        <span
                            v-on:click="deleteImage"
                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                            <i class="bi bi-x fs-2"></i>
                        </span></div>
                    <div class="form-text">Allowed file types: png, jpg, jpeg.</div>

                    <div v-if="errors.avatar" class="fv-plugins-message-container invalid-feedback">
                        <div data-field="daterangepicker_input" data-validator="notEmpty">{{ errors.avatar[0] }}</div>
                    </div>
                </div>
            </div>
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-semobold fs-6">E-mail</label>
                <div class="col-lg-8 fv-row">
                    <input v-model="model.email" type="text" class="form-control" placeholder="name@example.com">
                    <div v-if="errors.email" class="fv-plugins-message-container invalid-feedback">
                        <div data-field="daterangepicker_input" data-validator="notEmpty">{{ errors.email[0] }}</div>
                    </div>
                </div>
            </div>
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-semobold fs-6">Имя</label>
                <div class="col-lg-8 fv-row">
                    <input v-model="model.name" type="text" class="form-control" placeholder="">
                    <div v-if="errors.name" class="fv-plugins-message-container invalid-feedback">
                        <div data-field="daterangepicker_input" data-validator="notEmpty">{{ errors.name[0] }}</div>
                    </div>
                </div>
            </div>
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-semobold fs-6">Фамилия</label>
                <div class="col-lg-8 fv-row">
                    <input v-model="model.surname" type="text" class="form-control" placeholder="">
                    <div v-if="errors.surname" class="fv-plugins-message-container invalid-feedback">
                        <div data-field="daterangepicker_input" data-validator="notEmpty">{{ errors.surname[0] }}</div>
                    </div>
                </div>
            </div>
            <button ref="submitButton" href="javascript:;" v-on:click="submitForm" type="submit" class="btn btn-success">

                <span class="indicator-label"> Сохранить </span>
                <span class="indicator-progress">
                    Please wait...
                    <span
                        class="spinner-border spinner-border-sm align-middle ms-2"
                    ></span>
          </span>
            </button>
        </div>
    </div>
</template>

<script>
import ApiService from "../../core/services/ApiService";
import Swal from "sweetalert2";
import {useRouter} from "vue-router";
export default {
    name: "UserEdit",
    data() {
        return {
            errors: {},
            model: {
                email: '',
                avatar: '',
                name: '',
                surname: ''
            }
        }
    },
    mounted() {
        ApiService.get("user/info/"+this.$route.params.id)
            .then((response) => {
                console.log(response);
                this.model = response.data.data;
            })
    },
    methods: {
        submitForm() {
            ApiService.post("user/edit/"+this.$route.params.id, this.model)
                .then(() => {
                    Swal.fire({
                        text: "Клиент успешно изменен",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        heightAuto: false,
                        customClass: {
                            confirmButton: "btn fw-semobold btn-light-primary",
                        },
                    }).then(() => {
                        // Go to page after successfully login
                        this.$router.push({ name: "user-list" });
                    });
                })
                .catch(({ response }) => {
                    console.log(response)
                    this.errors = response.data.errors;
                    Swal.fire({
                        text: response.data.message,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Try again!",
                        heightAuto: false,
                        customClass: {
                            confirmButton: "btn fw-semobold btn-light-danger",
                        },
                    });
                });
        },
        deleteImage() {
            console.log('delete')
            this.model.avatar = '';
        },
        handleImage(e) {
            console.log('handleImage')
            const selectedImage = e.target.files[0];
            this.createBase64Image(selectedImage);
        },
        createBase64Image(fileObject) {
            const reader = new FileReader();

            reader.onload = (e) => {
                this.model.avatar = 'url(' + e.target.result + ')';
            }

            reader.readAsDataURL(fileObject);
        }
    },
}
</script>

<style scoped>

</style>
