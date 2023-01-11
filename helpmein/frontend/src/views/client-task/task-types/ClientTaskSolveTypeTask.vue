<template>
    <div class="card col-8">
        <div class="card-body" v-if="isVisible">
            <div class="row p-5">
                <div class="col-5 p-0">
                    Ответьте на вопросы по теме
                </div>
                <div class="col-3 p-0 offset-md-4">
                    <b>№ {{ model.id }}</b>
                </div>
            </div>
            <div class="row p-5">
                <div class="p-0">
                    <b>{{ model.name }}</b>
                </div>
            </div>
            <div class="row p-5">
                {{ model.description }}
            </div>
            <div class="row p-5">
                {{ model.comment_client }}
            </div>

            <div class="row p-5">
                <!-- Question start -->
                <template v-for="(item, index) of model.questions">
                    <div class="row p-0">
                        <div class="col-3 col-form-label fw-semobold fs-6">
                            <b>
                                {{ index + 1 }}.
                                {{ model.questions[index].title }}
                            </b>
                        </div>
                    </div>

                    <div class="form-group row">

                        <div class="row p-0">Выберите один из вариантов:</div>
                        <div class="radio-inline radio-box col-9 row" :class="{
                            'alert-danger': getQuestionAnswerClass(index)
                        }">
                            <template v-for="(answerItem, answerIndex) of model.questions[index].answers">
                                <div class="row">
                                    <div
                                        class="form-check form-check-custom form-check-solid form-check-sm me-5 form-flex p-1 me-5 form-flex p-1">
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
                                        <div class="p-2">
                                            <label :for="'model_question_answer_'+index+'_'+answerIndex">
                                                {{ model.questions[index].answers[answerIndex].title }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="col-3">
                        <button
                            :ref="'check_answer_' + index"
                            v-on:click="checkAnswer(index)"
                            class="btn-color-primary shadow btn btn-sm">
                            <span class="indicator-label"> Проверить ответ </span>
                            <span class="indicator-progress">
                                подождите...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </template>
                <!-- Question end -->
            </div>

            <div class="box justify-content-center pt-8">
                <div class="col-auto p-1">
                    <button ref="submitButton"
                            href="javascript:;"
                            v-on:click="submitForm"
                            type="submit"
                            class="btn-color-success shadow btn btn-sm">
                        <span class="indicator-label"> Отправить решение </span>
                        <span class="indicator-progress">
                            Пожалуйста подождите...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
                <div class="col-auto p-1">
                    <router-link to="/task/list">
                        <button
                            href="javascript:;"
                            type="submit"
                            class="btn-color-warning shadow btn btn-sm">
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
            question_answers: [],
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
    async beforeRouteEnter(to, from, next) {
        console.log(to, from, next)
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
                answer: task.answer?.answer,
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
        async checkAnswer(index) {
            let ref = 'check_answer_' + index;
            this.$refs[ref][0].disabled = true;
            this.$refs[ref][0].setAttribute("data-kt-indicator", "on");
            await ApiService.post("client/task/" + this.model.id + "/check-answer/" + index, this.model.questions[index])
                .then(() => {
                    this.question_answers[index] = true;
                })
                .catch(({response}) => {
                    this.question_answers[index] = false;
                });
            this.$refs[ref][0].disabled = false;
            this.$refs[ref][0].setAttribute("data-kt-indicator", "off");
        },
        async submitForm() {
            this.$refs.submitButton.disabled = true;
            this.$refs.submitButton.setAttribute("data-kt-indicator", "on");
            await ApiService.post("client/task/solve/" + this.model.id, this.model)
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
        getQuestionAnswerClass(index) {
            let result = this.question_answers[index];
            if (result === false) {
                return true;
            }
            return false;
        },
    },
    watch: {
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
