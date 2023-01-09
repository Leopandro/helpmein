<template>
    <div class="card row">
        <div class="card-header">
            <div class="card-title">
                {{ title }}
            </div>
        </div>
        <div class="card-body" v-if="isVisible">
            <div class="row mb-6" v-for="(value, name, index) in form">
                <template
                    v-if="value.type === 'text' || value.type === 'select'  || value.type === 'textarea' || value.type === 'datetime' ">
                    <label class="col-lg-2 col-form-label fw-semobold fs-6">{{ value.title }}</label>
                    <div v-if="value.type === 'text'" class="col-lg-10 fv-row">
                        <input v-model="model[value.name]" type="text" class="form-control">
                        <div v-if="errors[value.name]" class="fv-plugins-message-container invalid-feedback">
                            <div data-validator="notEmpty">{{
                                    errors[value.name][0]
                                }}
                            </div>
                        </div>
                    </div>
                    <div v-if="value.type === 'textarea'" class="col-lg-10 fv-row">
                        <textarea v-model="model[value.name]" class="form-control"></textarea>
                        <div v-if="errors[value.name]" class="fv-plugins-message-container invalid-feedback">
                            <div data-validator="notEmpty">{{
                                    errors[value.name][0]
                                }}
                            </div>
                        </div>
                    </div>
                    <div v-if="value.type === 'select'" class="col-lg-10 fv-row">
                        <select class="form-select" v-model="model[value.name]">
                            <option v-if="value.placeholder" value="">{{ value.placeholder }}</option>
                            <option v-for="option in value.options" :value="option.value">{{ option.title }}</option>
                        </select>
                        <div v-if="errors[value.name]" class="fv-plugins-message-container invalid-feedback">
                            <div data-validator="notEmpty">{{
                                    errors[value.name][0]
                                }}
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <div :class="{'disabled': model.type === 'essay'}">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h1>Вопросы</h1>
                        </div>
                    </div>
                    <div class="card-body">
                        <div v-for="(item, index) of model.questions">

                            <div class="card-body">
                                <div class="row">
                                    <label class="col-lg-2 col-form-label fw-semobold fs-6">
                                        Вопрос №{{ index + 1 }}
                                    </label>
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" v-model="model.questions[index].title"
                                               placeholder="Введите название вопроса"
                                               class="form-control form-control-lg form-control-solid">
                                        <span class="form-text text-muted">Название вопроса</span>
                                    </div>
                                    <div class="col-lg-2" v-on:click="removeQuestion(index)">
                                        <span class="svg-icon svg-icon-muted svg-icon-2hx" role="button">
                                            <svg width="24" height="24" viewBox="0 0 24 24"  xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="#E7505A"/>
                                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="#E7505A"/>
                                                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="#E7505A"/>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row">

                                    <div class="col-3">Правильных ответов</div>
                                    <div class="radio-inline radio-box col-3">
                                        <div class="form-check form-check-custom form-check-solid">
                                            <label class="form-check-label" for="flexRadioDefault1">
                                                <input class="form-check-input" type="radio" id="flexRadioDefault1"
                                                       v-model="model.questions[index].type" v-bind:value="'radio'">
                                                Один
                                            </label>
                                        </div>
                                        <div class="form-check form-check-custom form-check-solid">
                                            <label class="form-check-label" for="flexRadioDefault">
                                                <input class="form-check-input" type="radio" id="flexRadioDefault"
                                                       v-model="model.questions[index].type" v-bind:value="'checkbox'">
                                                Несколько
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">

                                    <div class="col-3">Варианты ответов</div>
                                    <div class="radio-inline radio-box col-9 row">
                                        <template v-for="(answerItem, answerIndex) of model.questions[index].answers">
                                            <div class="row">
                                                <div
                                                    class="form-check-custom form-check-inline form-check-solid me-5 form-flex p-1">
                                                    <input
                                                        v-if="model.questions[index].type === 'radio'"
                                                        class="form-check-input"
                                                        type="radio"
                                                        :id="'model_question_answer_'+index+'_'+answerIndex"
                                                        v-model="model.questions[index].radioValue"
                                                        v-bind:value="answerIndex">
                                                    <input
                                                        v-if="model.questions[index].type === 'checkbox'"
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        :id="'model_question_answer_'+index+'_'+answerIndex"
                                                        v-model="model.questions[index].answers[answerIndex].checkBoxValue"
                                                        v-bind:value="true">
                                                    <input type="text"
                                                           class="form-control form-control-sm form-control-solid"
                                                           placeholder="название ответа"
                                                           v-model="model.questions[index].answers[answerIndex].title"
                                                    >
                                                    <span class="svg-icon svg-icon-muted svg-icon-2hx"
                                                          v-on:click="removeAnswer(index, answerIndex)"
                                                          role="button">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                             xmlns="http://www.w3.org/2000/svg">
                                                            <rect opacity="0.8" x="6" y="17.3137" width="16" height="2"
                                                                  rx="1" transform="rotate(-45 6 17.3137)"
                                                                  fill="#E7505A"/>
                                                            <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                                                  transform="rotate(45 7.41422 6)" fill="#E7505A"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </template>
                                        <button class="btn btn-secondary btn-sm col-5" type="button"
                                                v-on:click="addAnswer(index)">Добавить ответ
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button :class="{'disabled': model.type === 'essay'}" type="button" v-on:click="addQuestion" class="btn btn-primary">Добавить вопрос</button>
            <br>
        </div>
        <div class="box">
            <button ref="submitButton"
                    href="javascript:;"
                    v-on:click="submitForm"
                    type="submit"
                    class="btn btn-success">
                <span class="indicator-label"> Сохранить </span>
                <span class="indicator-progress">
                    Пожалуйста подождите...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
            <router-link to="/task-category/list">
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
</template>

<script>
import ApiService from "../../core/services/ApiService";
import Swal from "sweetalert2";

export default {
    name: "TaskCreate",
    data() {
        return {
            errors: {},
            form: [
                {
                    name: 'type',
                    type: 'select',
                    title: 'Тип задачи',
                    options: [
                        {
                            value: 'essay',
                            title: 'Эссе'
                        },
                        {
                            value: 'task',
                            title: 'Задача'
                        },
                    ]
                },
                {
                    name: 'difficult_level',
                    type: 'select',
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
                    title: 'Комментарий (для клиента)'
                },
                {
                    name: 'comment',
                    type: 'textarea',
                    title: 'Комментарий (для преподавателя)'
                },
                {
                    name: 'questions',
                    type: 'questions',
                    title: 'Вопросы'
                },
            ],
            model: {
                id: '',
                type: 'essay',
                task_category_id: '',
                name: '',
                description: '',
                comment: '',
                comment_client: '',
                questions: [],
                difficult_level: '',
            }
        }
    },
    methods: {
        mappingFieldsFromTask(task) {
            return {
                id: task.id,
                type: task.type?.id,
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
            await ApiService.post(this.model.id ? "admin/task/edit/"+this.model.id : "admin/task/create", this.model)
                .then(() => {
                    Swal.fire({
                        text: this.model.id ? "Задача успешно обновлена" : "Задача успешно создана",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ок!",
                        heightAuto: false,
                        customClass: {
                            confirmButton: "btn fw-semobold btn-light-primary",
                        },
                    }).then(() => {
                        // Go to page after successfully login
                        this.$router.push({name: "task-category-list"});
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
            await ApiService.get("admin/task/info/" + this.$route.params.id).then((response) => {
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

<style scoped>
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

.disabled {
    display: none;
    position: relative;
}

.disabled:after {
    content: "";
    position: absolute;
    width: 100%;
    height: inherit;
    background-color: rgba(0, 0, 0, 0.1);
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
}
</style>

