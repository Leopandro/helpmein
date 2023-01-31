<template>
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                {{ $t('Редактировать профиль') }}
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label fw-semobold fs-6">{{ $t('Аватар') }}</label>
                <div class="col-lg-8">
                    <div class="image-input image-input-outline" data-kt-image-input="true"
                         style="background-image: url(&quot;/media/icons/duotune/communication/com006.svg&quot;);">
                        <div class="image-input-wrapper w-125px h-125px"
                             v-bind:style="imageUrl"></div>
                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"

                               data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar"><i
                            class="bi bi-pencil-fill fs-7"></i>
                            <input type="file" ref="fileInput" @change="handleImage" name="avatar" accept=".png, .jpg, .jpeg">
                            <input type="hidden" name="avatar_remove">
                        </label>
                        <span
                            v-on:click="deleteImage"
                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                            <i class="bi bi-x fs-2"></i>
                        </span></div>
                    <div class="form-text">{{$t('Разрешены расширения: png, jpg, jpeg.')}}</div>

                    <div v-if="errors.avatar" class="fv-plugins-message-container invalid-feedback">
                        <div data-field="daterangepicker_input" data-validator="notEmpty">{{ errors.avatar[0] }}</div>
                    </div>
                </div>
            </div>
            <div class="row mb-6" style="display: none">
                <label class="col-lg-4 col-form-label required fw-semobold fs-6">E-mail</label>
                <div class="col-lg-8 fv-row">
                    <input v-model="model.email" type="text" class="form-control" placeholder="name@example.com">
                    <div v-if="errors.email" class="fv-plugins-message-container invalid-feedback">
                        <div data-field="daterangepicker_input" data-validator="notEmpty">{{ errors.email[0] }}</div>
                    </div>
                </div>
            </div>
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-semobold fs-6">{{ $t('Имя') }}</label>
                <div class="col-lg-8 fv-row">
                    <input v-model="model.name" type="text" class="form-control" placeholder="">
                    <div v-if="errors.name" class="fv-plugins-message-container invalid-feedback">
                        <div data-field="daterangepicker_input" data-validator="notEmpty">{{ errors.name[0] }}</div>
                    </div>
                </div>
            </div>
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-semobold fs-6">{{ $t('Фамилия') }}</label>
                <div class="col-lg-8 fv-row">
                    <input v-model="model.surname" type="text" class="form-control" placeholder="">
                    <div v-if="errors.surname" class="fv-plugins-message-container invalid-feedback">
                        <div data-field="daterangepicker_input" data-validator="notEmpty">{{ errors.surname[0] }}</div>
                    </div>
                </div>
            </div>
            <button ref="submitButton" href="javascript:;" v-on:click="submitForm" type="submit" class="btn btn-success">

                <span class="indicator-label"> {{$t("Сохранить")}} </span>
                <span class="indicator-progress">
                    {{$t("Пожалуйста подождите...")}}
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
import {useAuthStore} from "../../stores/auth";
export default {
    name: "ProfileEdit",
    data() {
        return {
            errors: [],
            model: {
                email: '',
                avatar: '/media/avatars/blank.png',
                name: '',
                image: '',
                surname: ''
            }
        }
    },
    mounted() {
        const store = useAuthStore();
        store.errors = "";
        ApiService.get("user/profile/info/")
            .then((response) => {
                this.model = response.data.data;
                console.log(response.data.data)
                this.model.avatar = response.data.data?.avatar == undefined ? '/media/avatars/blank.png' : response.data.data.avatar.url;
                this.model.image = '';
            })
    },
    computed: {
        imageUrl: function() {
            return {'background-image': `url(${this.model.avatar})`}
        }
    },
    methods: {
        submitForm() {
            const store = useAuthStore();
            let formData = new FormData();
            formData.set('image', this.model.image)
            formData.set('email', this.model.email)
            formData.set('name', this.model.name)
            formData.set('surname', this.model.surname)
            ApiService.post("user/profile/edit/", formData)
                .then(() => {
                    Swal.fire({
                        text: this.$t("Профиль успешно изменен"),
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: this.$t("Ок!"),
                        heightAuto: false,
                        customClass: {
                            confirmButton: "btn fw-semobold btn-light-primary",
                        },
                    }).then(() => {
                        // Go to page after successfully login
                        this.$router.push({ path: "/" });
                    });
                })
                .catch(({ response }) => {
                    console.log(response)
                    this.errors = response.data.errors;
                    Swal.fire({
                        text: response.data.message,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ок!",
                        heightAuto: false,
                        customClass: {
                            confirmButton: "btn fw-semobold btn-light-danger",
                        },
                    });
                });
        },
        deleteImage() {
            this.model.avatar = '/media/avatars/blank.png';
            this.model.image = '';
            this.$refs.fileInput.value = '';
        },
        handleImage(e) {
            const selectedImage = e.target.files[0];
            this.model.image = selectedImage;
            this.createBase64Image(selectedImage);
        },
        createBase64Image(fileObject) {
            const reader = new FileReader();

            reader.onload = (e) => {
                this.model.avatar = e.target.result;
            }

            reader.readAsDataURL(fileObject);
        }
    },
}
</script>

<style scoped>

</style>
