<template>
    <div class="card col-12">
        <div class="card-body" v-if="isVisible">
            <div class="row p-3">
                <div class="col-auto p-0">
                    <b>{{$t('Ответьте на вопросы по теме')}}</b>
                </div>
                <div class="col-auto ms-auto text-secondary p-0">
                    <b>№ {{ model.id }}</b>
                </div>
            </div>
            <div class="row p-3">
                <div class="col-auto p-0">
                    <b>{{ model.name }}</b>
                </div>
            </div>
            <div class="row p-3">
                <div class="p-0">{{ model.description }}</div>
            </div>
            <div class="row p-3">
                <div class="p-0">{{ model.comment_client }}</div>
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

                        <div class="col-12 p-0">{{$t('Правильными могут быть несколько вариантов:')}}</div>
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
                                            disabled="disabled"
                                            :id="'model_question_answer_'+index+'_'+answerIndex"
                                            v-model="model.questions[index].radioValue"
                                            v-on:change="setRadioCheck(index, answerIndex)"
                                            v-bind:value="answerIndex">
                                        <input
                                            v-if="model.questions[index].type === 'checkbox'"
                                            class="form-check-input"
                                            type="checkbox"
                                            disabled="disabled"
                                            :id="'model_question_answer_'+index+'_'+answerIndex"
                                            v-model="model.questions[index].answers[answerIndex].checkBoxValue"
                                            v-bind:value="true">
                                        <div class="p-2" :class="{
                                            'text-success': answerItem['success'] === true,
                                            'text-danger': answerItem['success'] === false
                                        }">
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

            <div class="col-12 px-3 p-3" v-if="model.mistakes > 0">
                <div class="alert alert-danger m-0">
                    Тест не пройден <br>
                    Кол-во ошибок: <b>{{ model.mistakes }}</b>
                </div>
            </div>
            <div class="col-12 px-3 p-3" v-if="model.mistakes === 0">
                <div class="alert alert-success m-0">
                    Тест успешно пройден
                </div>
            </div>

            <div class="box justify-content-start p-3">
                <div class="col-auto p-1">

                    <router-link :to="getEditLink(model)">
                        <button
                            href="javascript:;"
                            type="submit"
                            class="btn btn-danger shadow btn btn-sm me-1">
                            <span class="indicator-label"> Пересдать </span>
                            <span class="indicator-progress">
                                {{$t('Пожалуйста подождите...')}}
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </router-link>

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
                mistakes: '',
                comment_client: '',
                questions: [],
                difficult_level: '',
                answer: ''
            }
        }
    },
    async beforeRouteEnter(to, from, next) {
        console.log(to, from, next)
        await ApiService.get("/client/task/result/" + to.params.id).then((response) => {
            // this.model = this.mappingFieldsFromTask(response.data.data);
            next((vm) => {
                vm.model = vm.mappingFieldsFromTask(response.data.data)
            })
        })
    },
    methods: {
        getEditLink(task) {
            return '/task/solve-' + task.type.id + '/'+ task.id;
        },
        setRadioCheck(index, answerIndex) {
            console.log(index, answerIndex);
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
                mistakes: task.mistakes,
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
            let result = await ApiService.get("/client/task/result/" + this.$route.params.id).then((response) => {
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
