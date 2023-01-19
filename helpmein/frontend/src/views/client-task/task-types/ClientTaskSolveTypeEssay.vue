<template>
    <div class="card col-8">
        <div class="card-body" v-if="isVisible">
            <div class="row p-5">
                <div class="col-1">
                    {{model.type.title}}
                </div>
                <div class="col-3">
                    <b>{{model.name}}</b>
                </div>
            </div>
            <div class="row p-5">
                <p class=""><b>Описание задачи: </b>{{model.description}}</p>
            </div>
            <div class="row p-5" v-if="model.comment_client">
                {{model.comment_client}}
            </div>
            <div class="row p-5" v-if="model.answer.teacher_comment">
                <p class=""><b>Комментарий преподавателя: </b>{{model.answer.teacher_comment}}</p>
            </div>

            <div class="row p-5">
                <div class="fw-semibold fs-6">Решение задачи</div>
            </div>

            <div class="row p-5">
                <textarea
                    v-model="model.answer.answer"
                    type="text"
                    rows="8"
                    class="form-control"
                    placeholder="Решение задачи"></textarea>
            </div>

            <div class="box justify-content-center pt-8">
                <button ref="submitButton"
                        href="javascript:;"
                        v-on:click="submitForm"
                        type="submit"
                        class="btn btn-success">
                    <span class="indicator-label"> Отправить решение </span>
                    <span class="indicator-progress">
                    Пожалуйста подождите...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
                </button>
                <router-link to="/task/list">
                    <button
                        href="javascript:;"
                        type="submit"
                        class="btn btn-danger">
                        <span class="indicator-label"> Отмена </span>
                        <span class="indicator-progress">
                    Пожалуйста подождите...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
                    </button>
                </router-link>
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
            form: [
                {
                    name: 'name',
                    type: 'text',
                    title: 'Название'
                },
                {
                    name: 'description',
                    type: 'textarea',
                    title: 'Описание задачи'
                },
                {
                    name: 'comment_client',
                    type: 'textarea',
                    title: 'Комментарий'
                },
                {
                    name: 'questions',
                    type: 'questions',
                    title: 'Вопросы'
                },
                {
                    name: 'difficult_level',
                    type: 'select',
                    placeholder: '-- Выберите сложность --',
                    options: [
                        {
                            value: 'A1',
                            title: 'A1'
                        },
                        {
                            value: 'A2',
                            title: 'A2'
                        },
                        {
                            value: 'B1',
                            title: 'B1'
                        },
                        {
                            value: 'B2',
                            title: 'B2'
                        },
                    ],
                    title: 'Уровень сложности'
                },
            ],
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
            // this.model = this.mappingFieldsFromTask(response.data.data);
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
                    Swal.fire({
                        text: "Ответ успешно сохранен",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ок!",
                        heightAuto: false,
                        customClass: {
                            confirmButton: "btn fw-semobold btn-light-primary",
                        },
                    }).then(() => {
                        // Go to page after successfully login
                        this.$router.push({name: "task-list"});
                    });
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
        title() {
            if (this.$route.params.id) {
                return this.$t("task.editTitle");
            } else {
                return this.$t("task.createTitle");
            }
        }
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
