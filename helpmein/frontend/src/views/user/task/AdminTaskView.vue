<template>
    <div class="card col-8">
        <div class="card-body" v-if="isVisible">
            <div class="row p-5">
                <div class="col-auto">
                    {{model.type.title}}
                </div>
                <div class="col-auto">
                    <b>{{model.name}}</b>
                </div>
            </div>
            <div class="row p-5" v-if="model.description">
                <p class="">{{model.description}}</p>
            </div>
            <div class="row p-5" v-if="model.comment_client">
                {{model.comment_client}}
            </div>
            <div class="row p-5" v-if="model.answer.teacher_comment">
                <div class=""><b>{{$t('Комментарий преподавателя:')}}</b>{{model.answer.teacher_comment}}</div>
            </div>

            <div class="row p-5">
                <div class="fw-semibold fs-6">{{$t('Решение задачи')}}</div>
            </div>

            <div class="row p-5">
                <div class="">{{model.answer.answer}}</div>
            </div>
            <div class="row p-5">
                <label for="exampleFormControlTextarea1" class="form-label">{{$t('Комментарий для клиента:') }}</label>
                <textarea class="form-control"
                          v-model="response.teacher_comment"
                          id="exampleFormControlTextarea1"
                          rows="3">
                </textarea>
                <div v-if="errors['teacher_comment']" class="fv-plugins-message-container invalid-feedback">
                    <div data-validator="notEmpty">{{
                            errors['teacher_comment'][0]
                        }}
                    </div>
                </div>
            </div>
            <div class="box justify-content-start pt-8">
                <div class="col-auto p-1">
                </div>
                <div class="col-auto p-1">
                    <button
                        v-on:click="acceptTask"
                        href="javascript:;"
                        type="submit"
                        ref="acceptButton"
                        class="btn btn-success shadow btn btn-sm me-1">
                        <span class="indicator-label">{{ $t('Принять ответ') }}</span>
                        <span class="indicator-progress">
                            {{$t('Пожалуйста подождите...')}}
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                    <button
                        v-on:click="declineTask"
                        href="javascript:;"
                        type="submit"
                        ref="declineButton"
                        class="btn btn-warning shadow btn btn-sm me-1">
                        <span class="indicator-label">{{ $t('Отправить на пересдачу') }}</span>
                        <span class="indicator-progress">
                            {{$t('Пожалуйста подождите...')}}
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import ApiService from "@/core/services/ApiService";
import Swal from "sweetalert2";

export default {
    name: 'AdminTaskView',
    data() {
        return {
            errors: {},
            form: [
                {
                    name: 'name',
                    type: 'text',
                    title: this.$t('Название')
                },
                {
                    name: 'description',
                    type: 'textarea',
                    title: this.$t('Описание задачи')
                },
                {
                    name: 'comment_client',
                    type: 'textarea',
                    title: this.$t('Комментарий')
                },
                {
                    name: 'questions',
                    type: 'questions',
                    title: this.$t('Вопросы')
                },
                {
                    name: 'difficult_level',
                    type: 'select',
                    placeholder: this.$t('-- Выберите сложность --'),
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
                    title: this.$t('Уровень сложности')
                },
            ],
            response: {
                comment: '',
            },
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
                answer: '',
                client: ''
            }
        }
    },
    methods: {
        mappingFieldsFromAnswer(task) {
            return {
                teacher_comment: task.answer.teacher_comment
            }
        },
        mappingFieldsFromTask(task) {
            return {
                id: task.id,
                answer: task.answer,
                client: task.client,
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
        async acceptTask() {
            this.$refs.acceptButton.disabled = true;
            this.$refs.acceptButton.setAttribute("data-kt-indicator", "on");
            await ApiService.post('/admin/user-task/accept/'+this.model.answer.id).then((response) => {
                Swal.fire({
                    text: this.$t('Эссе успешно принято'),
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ок!",
                    heightAuto: false,
                    customClass: {
                        confirmButton: "btn fw-semobold btn-light-primary",
                    },
                }).then(() => {
                    this.$router.push({ path: "/user/edit/"+this.model.client.id+"/task/" });
                })
            }).catch(({response}) => {
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
            this.$refs.acceptButton.disabled = false;
            this.$refs.acceptButton.setAttribute("data-kt-indicator", "off");
        },
        async declineTask() {
            this.$refs.declineButton.disabled = true;
            this.$refs.declineButton.setAttribute("data-kt-indicator", "on");
            await ApiService.post('/admin/user-task/decline/'+this.model.answer.id, this.response).then((response) => {
                Swal.fire({
                    text: this.$t('Эссе отправлено на доработку'),
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: this.$t("Ок!"),
                    heightAuto: false,
                    customClass: {
                        confirmButton: "btn fw-semobold btn-light-primary",
                    },
                }).then(() => {
                    this.$router.push({ path: "/user/edit/"+this.model.client.id+"/task/" });
                })
            }).catch(({response}) => {
                this.errors = response.data.errors;
                Swal.fire({
                    text: response.data.message,
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: this.$t("Ок!"),
                    heightAuto: false,
                    customClass: {
                        confirmButton: "btn fw-semobold btn-light-danger",
                    },
                });
            });
            this.$refs.declineButton.disabled = false;
            this.$refs.declineButton.setAttribute("data-kt-indicator", "off");
        },

        getQuestion() {
            return {
                title: '',
                checkBoxValue: ''
            };
        },
        async addAnswer(index) {
            this.model.questions[index].answers.push(this.getQuestion());
        },
        async getTask() {
            await ApiService.get("/admin/user-task/info/" + this.$route.params.id).then((response) => {
                this.model = this.mappingFieldsFromTask(response.data.data);
                this.response = this.mappingFieldsFromAnswer(response.data.data);
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
    mounted() {
        if (this.$route.params.id) {
            this.getTask();
        } else {
            this.model.task_category_id = this.$route.query.task_category_id;
            this.addQuestion();
        }

    }
}
</script>
<style>

</style>
