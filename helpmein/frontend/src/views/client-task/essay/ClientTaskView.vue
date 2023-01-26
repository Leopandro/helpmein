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
                <div class="">{{model.description}}</div>
            </div>
            <div class="row p-2" v-if="model.comment_client">
                <div>{{model.comment_client}}</div>
            </div>
            <div class="row p-2" v-if="model.answer.teacher_comment">
                <span class=""><span class="fw-bold">Комментарий преподавателя: </span>{{model.answer.teacher_comment}}</span>
            </div>

            <div class="row p-2">
                <div class="">{{model.answer.answer}}</div>
            </div>

            <div class="box justify-content-start pt-1">
                <div class="col-auto p-1">
                    <router-link to="/task/list">
                        <button
                            href="javascript:;"
                            type="submit"
                            class="btn btn-secondary shadow btn btn-sm me-1">
                            <span class="indicator-label"> К списку задач </span>
                            <span class="indicator-progress">
                                Пожалуйста подождите...
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
import ApiService from "../../../core/services/ApiService";
import Swal from "sweetalert2";

export default {
    name: 'ClientTaskSolve',
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
            await ApiService.get("/client/task/info/" + this.$route.params.id).then((response) => {
                this.model = this.mappingFieldsFromTask(response.data.data);
            })
        },
        async removeAnswer(questionIndex, answerIndex) {
            this.model.questions[questionIndex].answers.splice(answerIndex, 1);
        },
        async addQuestion() {
            this.model.questions.push({
                title: '',
                type: 'radio',
                radioValue: '',
                answers: [
                    this.getQuestion()
                ]
            });
        },
        async removeQuestion(index) {
            this.model.questions.splice(index, 1);
        }
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
