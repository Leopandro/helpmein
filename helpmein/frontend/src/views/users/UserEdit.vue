<template>
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                Редактировать клиента
            </div>
        </div>
        <div class="card-body">
            <div class="mb-10">
                <label class="form-label">E-mail</label>
                <input v-model="model.email" type="text" class="form-control" placeholder="name@example.com">
                <div v-if="errors.email" class="fv-plugins-message-container invalid-feedback">
                    <div data-field="daterangepicker_input" data-validator="notEmpty">{{ errors.email[0] }}</div>
                </div>
            </div>
            <div class="mb-10">
                <label class="form-label">Имя</label>
                <input v-model="model.first_name" type="text" class="form-control" placeholder="Имя">
                <div v-if="errors.first_name" class="fv-plugins-message-container invalid-feedback">
                    <div data-field="daterangepicker_input" data-validator="notEmpty">{{ errors.first_name[0] }}</div>
                </div>
            </div>
            <div class="mb-10">
                <label class="form-label">Фамилия</label>
                <input v-model="model.last_name" type="text" class="form-control" placeholder="Фамилия">
                <div v-if="errors.last_name" class="fv-plugins-message-container invalid-feedback">
                    <div data-field="daterangepicker_input" data-validator="notEmpty">{{ errors.last_name[0] }}</div>
                </div>
            </div>
            <a href="javascript:;" v-on:click="submitForm" class="btn btn-success">Сохранить</a>
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
                first_name: '',
                last_name: ''
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
        }
    },
}
</script>

<style scoped>

</style>
