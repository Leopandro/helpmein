<template>
    <div class="card col-12">
        <div class="card-body" v-if="isVisible">
            <div class="row p-3">
                <div class="col-5 p-0">
                    <b>{{$t('Ответьте на вопросы по теме')}}</b>
                </div>
                <div class="col-auto ms-auto p-0 text-secondary">
                    <b>№ {{ model.id }}</b>
                </div>
            </div>
            <div class="row p-3">
                <div class="p-0">
                    <b>{{ model.name }}</b>
                </div>
            </div>
            <div class="row p-3">
                {{ model.description }}
            </div>
            <div class="row p-3">
                {{ model.comment_client }}
            </div>

            <div class="row p-3">
                <!-- Question start -->
                <template v-for="(item, index) of model.questions">
                    <div class="col-auto p-0">
                        <div class="col-auto col-form-label fw-semobold fs-6">
                            <b>
                                {{ index + 1 }}.
                                {{ model.questions[index].title }}
                            </b>
                        </div>
                    </div>

                    <div class="form-group row p-3">

                        <div class="col-12 p-0">{{getLabelByQuestion(item)}}</div>
                        <div class="radio-inline radio-box col-9 row" :class="{
                            'alert-danger': getQuestionAnswerResult(index) === false,
                            'alert-success': getQuestionAnswerResult(index) === true
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
                                            v-on:change="setRadioCheck(index, answerIndex)"
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
                </template>
                <!-- Question end -->
            </div>

            <div class="box justify-content-start pt-8">
                <div class="col-auto p-1">
                    <button ref="submitButton"
                            href="javascript:;"
                            v-on:click="submitForm"
                            type="submit"
                            class="btn-color-success shadow btn btn-sm">
                        <span class="indicator-label">{{$t('Отправить решение')}}</span>
                        <span class="indicator-progress">
                            {{$t('Пожалуйста подождите...')}}
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
                <div class="col-auto p-1">
                    <router-link to="/task/list">
                        <button
                            href="javascript:;"
                            type="submit"
                            class="btn-color-dark shadow btn btn-sm">
                            <span class="indicator-label"> {{$t('Отмена')}} </span>
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
import {cloneDeep} from "lodash";

export default {
    name: 'ClientTaskSolveTypeTask',
    data() {
        return {
            errors: {},
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
        getLabelByQuestion(question) {
            if (question.type === 'radio') {
                return "Выберите один из вариантов:";
            } else {
                return "Правильными могут быть несколько вариантов:";
            }
        },
        setRadioCheck(index, answerIndex) {
            this.model.questions[index].answers.forEach((item, itemIndex) => {
                if (itemIndex === answerIndex) {
                    item.checkBoxValue = true;
                } else {
                    item.checkBoxValue = false;
                }
            })
        },
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
        parseModel(index) {
            var model = cloneDeep(this.model);
            let radioValue = model.questions[index].radioValue;
            let questionType = model.questions[index].type;
            let question = model.questions[index];
            if (questionType === 'checkbox') {
                return question;
            }
            if (questionType === 'radio' && !(radioValue === null) && (radioValue >= 0)){
                question.answers[radioValue].checkBoxValue = true;
            } else {
            }
            console.log(question.answers);
            console.log(model.questions[index].answers);
            return question;
        },
        async checkAnswer(index) {
            let ref = 'check_answer_' + index;
            this.$refs[ref][0].disabled = true;
            this.$refs[ref][0].setAttribute("data-kt-indicator", "on");
            let question = this.parseModel(index);
            await ApiService.post("client/task/" + this.model.id + "/check-answer/" + index, question)
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
                    this.$router.push({path: '/task/solve-' + this.model.type.id + '-result/'+ this.model.id});
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
        getQuestionAnswerResult(index) {
            let result = this.question_answers[index];
            if (result === undefined) {
                return undefined;
            }
            if (result === true) {
                return true;
            }
            if (result === false) {
                return false;
            }
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
    },
    async created() {
        console.log(this.question_answers);
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
