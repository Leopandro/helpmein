<template>
    <div class="card col-6">
        <div class="card-header">
            <div class="card-title">
                {{  title }}
            </div>
        </div>
        <div class="card-body" v-if="isVisible">
            <div class="row mb-6" v-for="(value, name, index) in form">
                <template v-if="value.type === 'text' || value.type === 'select' ">
                    <label class="col-lg-2 col-form-label fw-semobold fs-6">{{ value.title }}</label>
                    <div v-if="value.type === 'text'" class="col-lg-10 fv-row">
                        <input v-model="model[value.name]" type="text" class="form-control">
                        <div v-if="errors[value.name]" class="fv-plugins-message-container invalid-feedback">
                            <div data-field="daterangepicker_input" data-validator="notEmpty">{{
                                    errors[value.name][0]
                                }}
                            </div>
                        </div>
                    </div>
                    <div v-if="value.type === 'select'" class="col-lg-10 fv-row">
                        <select class="form-select" v-model="model[value.name]">
                            <option>{{ value.placeholder }}</option>
                            <option v-for="option in value.options" :value="option.value">{{ option.title }}</option>
                        </select>
                        <div v-if="errors[value.name]" class="fv-plugins-message-container invalid-feedback">
                            <div data-field="daterangepicker_input" data-validator="notEmpty">{{
                                    errors[value.name][0]
                                }}
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <div class="">
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
                                               class="form-control form-control-lg form-control-solid">
                                        <span class="form-text text-muted">Название вопроса</span>
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
                                                <div class="form-check form-check-inline form-check-solid me-5 form-flex">
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
                                                           class="form-control"
                                                           placeholder="Введите название вопроса"
                                                           v-model="model.questions[index].answers[answerIndex].title"
                                                    >
                                                    <label class="form-check-label" :for="'model_question_answer_'+index+'_'+answerIndex">
                                                    </label>
                                                </div>
                                            </div>
                                        </template>
                                        <button class="btn btn-primary btn-sm col-3" type="button" v-on:click="addAnswer(index)">Добавить ответ</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" v-on:click="addQuestion" class="btn btn-success">Добавить вопрос</button>
            <br>
            <button ref="submitButton" href="javascript:;" v-on:click="submitForm" type="submit"
                    class="btn btn-success">

                <span class="indicator-label"> Сохранить </span>
                <span class="indicator-progress">
                    Пожалуйста подождите...
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

export default {
    name: "TaskCreate",
    data() {
        return {
            title: '',
            errors: {},
            form: [
                {
                    name: 'type',
                    type: 'select',
                    placeholder: '-- Выберите тип --',
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
                    name: 'name',
                    type: 'text',
                    title: 'Название'
                },
                {
                    name: 'description',
                    type: 'text',
                    title: 'Описание'
                },
                {
                    name: 'comment',
                    type: 'text',
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
                type: '',
                name: '',
                description: '',
                comment: '',
                questions: [],
                difficult_level: '',
            }
        }
    },
    methods: {
        async submitForm() {
            this.$refs.submitButton.disabled = true;
            // Activate indicator
            this.$refs.submitButton.setAttribute("data-kt-indicator", "on");
            console.log(this.$refs.submitButton)
            await ApiService.post("task/create", this.model)
                .then(() => {
                    Swal.fire({
                        text: "Задача успешно создана",
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
            await ApiService.get("task/info/"+this.$route.params.id).then((response) => {
                console.log(response.data.data);
                this.model = response.data.data;
                console.log(this.model);
            })
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
        }
    },
    computed: {
        isVisible() {
            console.log(this.$route.params.id);
            if (this.$route.params.id) {
                if (this.model.id) {
                    return true;
                }
                return false;
            }
            return true;
        },
    },
    mounted() {
        if (this.$route.params.id) {
            this.title = "Редактировать задачу";
            this.getTask();
        } else {
            this.title = "Создать задачу";
        }
        this.addQuestion();
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
</style>

