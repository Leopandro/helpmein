<template>
    <div class="card row">
        <div class="card-body pe-none" v-if="isVisible">
            <div class="row mb-6">
                <div class="col-6">
                    <div class="row fv-row">
                        <label class="col-4 col-form-label fw-semobold fs-6">{{ $t(formType['type'].title) }}</label>
                        <div class="col-8 fv-row">
                            <select class="form-select" v-model="model[formType['type'].name]" disabled>
                                <option v-if="formType['type'].placeholder" value="">{{ formType['type'].placeholder }}</option>
                                <option v-for="option in formType['type'].options" :value="option.value">{{ $t(option.title) }}</option>
                            </select>
                            <div v-if="errors[formType['type'].name]" class="fv-plugins-message-container invalid-feedback">
                                <div data-validator="notEmpty">{{
                                        errors[formType['type'].name][0]
                                    }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="row fv-row">
                        <label class="col-4 col-form-label fw-semobold fs-6">{{ $t(formType['difficult_level'].title) }}</label>
                        <div class="col-8 fv-row">
                            <select class="form-select" v-model="model[formType['difficult_level'].name]" disabled>
                                <option v-if="formType['difficult_level'].placeholder" value="">{{ formType['difficult_level'].placeholder }}</option>
                                <option v-for="option in formType['difficult_level'].options" :value="option.value">{{ $t(option.title) }}</option>
                            </select>
                            <div v-if="errors[formType['difficult_level'].name]" class="fv-plugins-message-container invalid-feedback">
                                <div data-validator="notEmpty">{{
                                        errors[formType['difficult_level'].name][0]
                                    }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-6" v-for="(value, name, index) in form">
                <template
                    v-if="value.type === 'text' || value.type === 'select'  || value.type === 'textarea' || value.type === 'datetime' ">
                    <label class="col-lg-2 col-form-label fw-semobold fs-6">{{ $t(value.title) }}</label>
                    <div v-if="value.type === 'text'" class="col-lg-10 fv-row">
                        <input v-model="model[value.name]" type="text" class="form-control" disabled>
                        <div v-if="errors[value.name]" class="fv-plugins-message-container invalid-feedback">
                            <div data-validator="notEmpty">{{
                                    errors[value.name][0]
                                }}
                            </div>
                        </div>
                    </div>
                    <div v-if="value.type === 'textarea'" class="col-lg-10 fv-row">
                        <textarea v-model="model[value.name]" class="form-control" disabled></textarea>
                        <div v-if="errors[value.name]" class="fv-plugins-message-container invalid-feedback">
                            <div data-validator="notEmpty">{{
                                    errors[value.name][0]
                                }}
                            </div>
                        </div>
                    </div>
                    <div v-if="value.type === 'select'" class="col-lg-10 fv-row">
                        <select class="form-select" v-model="model[value.name]" disabled>
                            <option v-if="value.placeholder" value="">{{ value.placeholder }}</option>
                            <option v-for="option in value.options" :value="option.value">{{ $t(option.title) }}</option>
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
            <div v-if="model.type == 'task'" :class="{'disabled': model.type !== 'task'}">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h1>{{$t('??????????????')}}</h1>
                        </div>
                    </div>
                    <div class="card-body">
                        <div v-for="(item, index) of model.questions">

                            <div class="card-body">
                                <div class="row">
                                    <label class="col-2 col-form-label fw-semobold fs-6">
                                        {{$t('???????????? ???')}}{{ index + 1 }}
                                    </label>
                                    <div class="col-10 radio-inline radio-box row">
                                        <div class="col-8 p-0">
                                            <input type="text" v-model="model.questions[index].title"
                                                   :placeholder="$t('?????????????? ???????????????? ??????????????')"
                                                   class="form-control form-control-lg form-control-solid">
                                            <div v-if="errors['questions.'+index+'.title']" class="fv-plugins-message-container invalid-feedback">
                                                <div>
                                                    {{errors['questions.'+index+'.title'][0]}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row min-h-50px align-items-center">

                                    <div class="col-2">{{$t('???????????????????? ??????????????')}}</div>
                                    <div class="col-8 p-0">
                                        <div class="radio-inline radio-box col-8">
                                            <div class="form-check form-check-custom form-check-solid">
                                                <label class="form-check-label m-0" :for="'input_'+index+'_radio'">
                                                    <input class="form-check-input" type="radio" :id="'input_'+index+'_radio'"
                                                           v-model="model.questions[index].type" v-bind:value="'radio'">
                                                    {{$t('????????')}}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-custom form-check-solid">
                                                <label class="form-check-label ms-3" :for="'input_'+index+'_checkbox'">
                                                    <input class="form-check-input" type="radio" :id="'input_'+index+'_checkbox'"
                                                           v-model="model.questions[index].type" v-bind:value="'checkbox'">
                                                    {{$t('??????????????????')}}
                                                </label>
                                            </div>
                                        </div>
                                        <div v-if="errors['questions.'+index+'.type']" class="fv-plugins-message-container invalid-feedback">
                                            <div>
                                                {{errors['questions.'+index+'.type'][0]}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">

                                    <div class="col-2 pt-3">{{$t('???????????????? ??????????????')}}</div>
                                    <div class="col-10 radio-inline radio-box row">
                                        <template v-for="(answerItem, answerIndex) of model.questions[index].answers">
                                            <div
                                                class="form-check-custom form-check-inline form-check-solid me-5 form-flex p-1 px-0 col-12">
                                                <div class="col-8 p-0 d-flex align-items-center">
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        :id="'model_question_answer_'+index+'_'+answerIndex"
                                                        v-model="model.questions[index].answers[answerIndex].checkBoxValue">
                                                    <input type="text"
                                                           :ref="'answer_'+index+'_'+answerIndex"
                                                           class="form-control form-control-sm form-control-solid"
                                                           :placeholder="$t('?????????? ????????????')"
                                                           v-model="model.questions[index].answers[answerIndex].title">
                                                </div>
                                            </div>
                                            <div v-if="errors['questions.'+index+'.answers.'+answerIndex+'.title']"  class="fv-plugins-message-container invalid-feedback m-0">
                                                <div>
                                                    {{errors['questions.'+index+'.answers.'+answerIndex+'.title'][0]}}
                                                </div>
                                            </div>
                                            <div v-if="errors['questions.'+index+'.answers.'+answerIndex+'.checkBoxValue']" class="fv-plugins-message-container invalid-feedback m-0">
                                                <div>
                                                    {{errors['questions.'+index+'.answers.'+answerIndex+'.checkBoxValue'][0]}}
                                                </div>
                                            </div>
                                        </template>

                                        <div v-if="errors['questions.'+index+'.answers']" class="fv-plugins-message-container invalid-feedback">
                                            <div>
                                                {{errors['questions.'+index+'.answers'][0]}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
        </div>
        <div class="box align-items-center">

            <div class="col-auto p-3">
                <router-link to="/task-category/list">
                    <button
                        href="javascript:;"
                        type="submit"
                        class="btn-secondary shadow btn">
                        <span class="indicator-label"> {{$t('?? ???????????? ??????????')}} </span>
                        <span class="indicator-progress">
                        {{$t('???????????????????? ??????????????????...')}}
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                    </button>
                </router-link>
            </div>
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
                    name: 'name',
                    type: 'text',
                    title: '????????????????'
                },
                {
                    name: 'description',
                    type: 'textarea',
                    title: '???????????????? ????????????'
                },
                {
                    name: 'comment_client',
                    type: 'textarea',
                    title: '?????????????????????? (?????? ??????????????)'
                },
                {
                    name: 'comment',
                    type: 'textarea',
                    title: '?????????????????????? (?????? ??????????????????????????)'
                },
                {
                    name: 'questions',
                    type: 'questions',
                    title: '??????????????'
                },
            ],
            formType: {
                type: {
                    name: 'type',
                    type: 'select',
                    placeholder: '-- ???? ?????????????? --',
                    title: '?????? ????????????',
                    options: [
                        {
                            value: 'essay',
                            title: '????????'
                        },
                        {
                            value: 'task',
                            title: '????????'
                        },
                    ]
                },
                difficult_level: {
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
                    title: '?????????????? ??????????????????'
                },
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
        parseModel() {
            let model = this.model;
            return model;
        },
        async submitForm() {
            this.$refs.submitButton.disabled = true;
            this.$refs.submitButton.setAttribute("data-kt-indicator", "on");
            let parsedModel = this.parseModel();
            await ApiService.post(this.model.id ? "admin/task/edit/"+this.model.id : "admin/task/create", parsedModel)
                .then(() => {
                    Swal.fire({
                        text: this.model.id ? this.$t("???????????? ?????????????? ??????????????????") : this.$t("???????????? ?????????????? ??????????????"),
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: this.$t("????!"),
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
                        confirmButtonText: this.$t("????!"),
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
                checkBoxValue: false
            };
        },
        async addAnswer(index) {
            this.model.questions[index].answers.push(this.getQuestion());
            let ref = 'answer_' + index + '_' + (this.model.questions[index].answers.length - 1);
            console.log(this.$refs);
            console.log(ref);
            setTimeout(() => {
                this.$refs[ref][0].focus();
            }, 1);
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
</style>

