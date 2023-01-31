<template>
    <div class="card col-12">
        <div class="p-3" v-if="isVisible">
            <div class="row p-2">
                <div class="col-auto">
                    {{model.type.title}}
                </div>
                <div class="col-auto">
                    <b>{{model.name}}</b>
                </div>
            </div>
            <div class="row p-2" v-if="model.description">
                <div>{{model.description}}</div>
            </div>
            <div class="row p-2" v-if="model.comment_client">
                <div>{{model.comment_client}}</div>
            </div>
            <div class="row p-2" v-if="model.answer.teacher_comment">
                <div class=""><span class="fw-bold">{{$t('Комментарий преподавателя:')}} </span>{{model.answer.teacher_comment}}</div>
            </div>

            <div class="row p-4">
                <textarea
                    v-model="model.answer.answer"
                    type="text"
                    rows="8"
                    class="form-control"
                    :placeholder="$t('Комментарий преподавателя:')"></textarea>
            </div>
            <div class="box justify-content-start pt-1">
                <div class="col-auto p-1">
                    <button
                        v-on:click="submitForm"
                        href="javascript:;"
                        type="submit"
                        ref="submitButton"
                        class="btn btn-success shadow btn btn-sm me-1">
                        <span class="indicator-label"> {{$t('Отправить решение')}} </span>
                        <span class="indicator-progress">
                            {{$t('Пожалуйста подождите...')}}
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                    <router-link to="/task/list">
                        <button
                            href="javascript:;"
                            type="submit"
                            class="btn btn-secondary shadow btn btn-sm me-1">
                            <span class="indicator-label"> {{$t('К списку задач')}} </span>
                            <span class="indicator-progress">
                            {{$t('Пожалуйста подождите...')}}
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                        </button>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import ApiService from "@/core/services/ApiService";
import Swal from "sweetalert2";

export default {
    name: 'ClientTaskSolveTypeTask',
    data() {
        return {
            errors: {},
            model: {
                id: '',
                type: '',
                task_category_id: '',
                name: '',
                description: '',
                comment: '',
                comment_client: '',
                questions: [],
                difficult_level: '',
                answer: ''
            }
        }
    },
    async beforeRouteEnter(to,from,next) {
        console.log(to,from,next)
        await ApiService.get("/client/task/info/" + to.params.id).then((response) => {
            next((vm) => {
                vm.model = vm.mappingFieldsFromTask(response.data.data)
            })
        })
    },
    methods: {
        mappingFieldsFromTask(task) {
            return {
                id: task.id,
                answer: task.answer,
                type: task.type,
                name: task.name,
                description: task.description,
                comment: task.comment,
                questions: task.questions,
                difficult_level: task.difficult_level,
                comment_client: task.comment_client,
                task_category_id: task.task_category_id,
            };
        },
        async submitForm() {
            this.$refs.submitButton.disabled = true;
            this.$refs.submitButton.setAttribute("data-kt-indicator", "on");
            await ApiService.post("client/task/solve/"+this.model.id, this.model)
                .then(() => {
                    this.$router.push({name: "task-list"});
                })
                .catch(({response}) => {
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

            this.$refs.submitButton.disabled = false;
            this.$refs.submitButton.setAttribute("data-kt-indicator", "off");
        },
        async getTask() {
            await ApiService.get("/client/task/info/" + this.$route.params.id).then((response) => {
                this.model = this.mappingFieldsFromTask(response.data.data);
            })
        },
    },
    computed: {
        isVisible() {
            if (this.$route.params.id) {
                return true;
                return false;
            }
            return true;
        },
    },
    async created() {
        console.log(this.$route.params.id);
        if (this.$route.params.id) {
            let result = await ApiService.get("/client/task/info/" + this.$route.params.id).then((response) => {
                this.model = this.mappingFieldsFromTask(response.data.data);
            })
            console.log(result);
        }
    }
}
</script>
<style>

.box {
    display: flex;
    align-items: center;
    justify-content: center;
}

.radio-box {
    display: flex;
}

.form-flex {
    display: flex;
    align-items: center;
}
</style>
