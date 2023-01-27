<template>
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <button :class="{'btn-primary': taskStatus === 'all'}" v-on:click="setStatus('all')" type="button" class="btn btn-sm btn-light">Все задачи</button>
                <button :class="{'btn-primary': taskStatus === 'assigned'}" v-on:click="setStatus('assigned')" type="button" class="btn btn-sm btn-light">В работе</button>
                <button :class="{'btn-primary': taskStatus === 'in_review'}" v-on:click="setStatus('in_review')" type="button" class="btn btn-sm btn-light">На проверку</button>
            </div>
            <div class="card-toolbar">
                <div
                    class="d-flex justify-content-end"
                    data-kt-customer-table-toolbar="base"
                >
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <table class="table table-row-dashed fs-6 gy-5 my-0" v-if="tasks?.length > 0">
                <thead>
                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-25px">Статус</th>
                    <th class="min-w-25px">Уровень</th>
                    <th class="min-w-50px">Тип</th>
                    <th class="min-w-125px">Тема</th>
                    <th class="min-w-50px">Название задачи</th>
                    <th class="min-w-125px">Комментарий</th>
                    <th class="min-w-75px">Назначена</th>
                    <th class="min-w-25px text-end">Действия</th>
                    <th class="w-25px">Id</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="task in tasks">
                    <td>
                        <GetSvgByStatus :status="task.status.id"></GetSvgByStatus>
                    </td>
                    <th>{{task.difficult_level}}</th>
                    <th>{{task.type.title}}</th>
                    <td>
                        {{task.task_category}}
                    </td>
                    <td>
                        {{task.name}}
                    </td>
                    <th class="text-break">
                        <p v-if="task.comment">
                            <b>Для себя:</b> {{task.comment}}<br>
                        </p>
                        <p v-if="task.comment_client">
                            <b>Для клиента:</b>
                            {{task.comment_client}}
                        </p>
                    </th>
                    <th>{{
                            getFormattedDate(task.created_at)
                        }}</th>
                    <td class="text-end">

                        <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Действия
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <template v-if="task.type.id === 'essay'">
                                    <li v-if="['reassigned','in_review','finished'].includes(task.status.id)">
                                        <router-link class="dropdown-item" :to="viewTask(task)">
                                            Просмотр ответа
                                        </router-link>
                                    </li>
                                </template>
                                <li v-if="task.status.id === 'assigned'" v-on:click="removeTask(task.id)">
                                    <a class="dropdown-item" href="javascript:;">
                                        Удалить
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                    <th>{{task.id}}</th>
                </tr>
                </tbody>
            </table>
            <div class="col-12">
                <PaginationTemplate v-if="tasks?.length > 0" :count="pagesCount" :current-page="currentPage" :per-page="perPage" :values="[10,50]"></PaginationTemplate>
            </div>
            <div v-if="tasks?.length == 0" class="alert alert-primary">{{getErrorMessage()}}</div>
        </div>
    </div>
</template>

<script>
import ApiService from "@/core/services/ApiService";
import GetSvgByStatus from "@/views/client-task/_helpers/GetSvgByStatus.vue";
import PaginationTemplate from "@/components/table/PaginationTemplate.vue";
import moment from 'moment';
import {useRoute} from "vue-router/dist/vue-router";
import {useRouterStore} from "../../stores/router";
import {computed} from "vue";

export default {
    name: "UserTaskList",
    components: {
        PaginationTemplate,
        GetSvgByStatus
    },
    data() {
        return {
            currentPage: 1,
            perPage: 10,
            pagesCount: null,
            tableData: [],
            search: [],
            selectedIds: [],
            deleteFewCustomers: [],
            sort: [],
            client: {},
            tasks: null,
            taskStatus: 'all',
            loading: null,
            taskStatuses: {
                'all': {
                    title: 'Все задачи',
                    errorMessage: 'Нет назначенных задач',
                },
                'assigned': {
                    title: 'В работе',
                    errorMessage: 'Нет задач в работе',
                },
                'in_review': {
                    title: 'На проверку',
                    errorMessage: 'Нет задач на проверку',
                },
            },
        }
    },
    init() {
    },

    methods: {
        getErrorMessage() {
            if (this.loading === true) {
                return "Загрузка...";
            }
            if (this.tasks.length === 0) {
                return this.taskStatuses[this.taskStatus].errorMessage;
            }
        },
        viewTask(task) {
            return '/admin/task/view-' + task.type.id + '-result/'+ task.id;
        },
        async removeTask(id) {
            if (confirm('Вы уверены?')) {
                await ApiService.post('/admin/user-task-tree/delete', {
                    user_id: this.$route.params.id,
                    task_id: id
                }).then((response) => {
                    this.loadData()
                })
            }
        },
        getFormattedDate(date) {
            return moment(date).format('DD.MM.Y')
        },
        async loadData() {
            let filter  = {
                user_id: this.$route.params.id
            };
            this.taskStatus ? filter[this.taskStatus] = true : filter[this.taskStatus];
            this.loading = true;
            await ApiService.query('/admin/user-task/list', {
                params: {
                    filter: filter,
                    page: this.currentPage,
                    count: this.perPage
                },
            }).then((response) => {
                this.tasks = response.data.data.items;
                this.pagesCount = response.data.data.meta.pages_count;
            });
            this.loading = false;
        },
        setTasks(items) {
            this.tasks = items;
            const store = useRouterStore();
            this.client = items[0]['clients'][0];
            store.currentTitle = "Список задач клиента " + this.client.surname + ' ' + this.client.name;
        },
        async setStatus(status) {
            this.taskStatus = status;
            this.currentPage = 1;
            await this.loadData();
        },
    },
    async beforeRouteEnter(to, from, next) {
        let filter  = {
            user_id: to.params.id,
            all: true
        };
        await ApiService.query("/admin/user-task/list", {
            params: {
                filter: filter,
                page: 1,
                count: 10
            },
        }).then((response) => {
            // this.model = this.mappingFieldsFromTask(response.data.data);
            next((vm) => {
                vm.setTasks(response.data.data.items);
                vm.pagesCount = response.data.data.meta.pages_count;
            })
        })
    },
    async mounted() {
        const route = useRoute();
        this.emitter.on("change-page", (index) => {
            this.currentPage = index;
            this.loadData();
        });
        this.emitter.on("change-count", (count) => {
            this.currentPage = 1;
            this.perPage = count;
            this.loadData();
        });
    },
    unmounted() {
        this.emitter.off("change-count");
        this.emitter.off("change-page");
    },
    setup() {
        const router = useRouterStore();
        const email = computed(() => {
            const store = useRouterStore();
            return store.currentTitle;
        });
        return {
            email,
        }
    }
};
</script>
<style>
.form-check-label {
    font-weight: 300;
    font-size: 1rem;

}
</style>
