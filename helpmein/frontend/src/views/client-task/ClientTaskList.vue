<template>
    <div class="col-lg-8 col-md-10 col-sm-12">
        <div class="card-title">
            <button
                    v-on:click="setStatus(index)"
                    v-for="(taskStatusItem, index) in taskStatuses"
                    :class="{'btn-secondary': taskStatus === index}"
                    type="button"
                    class="btn btn-sm btn-light">{{ taskStatusItem.title }}</button>
        </div>
        <div class="col-lg-12 p-1 task-list-item" v-for="(task, name, index) in tasks">
            <div class="card h-100">

                <div class="card-header flex-nowrap border-0 pt-1">
                    <!--begin::Card title-->
                    <div class="card-title m-0">
                        <GetSvgByStatus :status="task.status.id"></GetSvgByStatus>
                        <!--begin::Icon-->
                        <div class="px-2 h6">
                            {{task.type.title}}
                        </div>
                        <div class="px-3">
                            <template v-if="task.type.id === 'essay'">
                                <router-link v-if="['in_review','finished'].includes(task.status.id)"
                                            :to="'/task/view/'+task.id"
                                            class="dropdown-item btn btn-secondary shadow btn btn-sm me-1">
                                    <h4><b>{{task.name}}</b></h4>
                                </router-link>
                                <router-link
                                            v-if="['assigned','reassigned'].includes(task.status.id)"
                                            :to="getEditLink(task)"
                                            class="dropdown-item btn btn-secondary shadow btn btn-sm me-1">
                                    <h4><b>{{task.name}}</b></h4>
                                </router-link>
                            </template>
                            <template v-if="task.type.id === 'task'">
                                <router-link v-if="['in_review','finished'].includes(task.status.id)"
                                             :to="getResultLink(task)"
                                             class="dropdown-item btn btn-secondary shadow btn btn-sm me-1">
                                    <h4><b>{{task.name}}</b></h4>
                                </router-link>
                                <router-link v-if="['assigned','reassigned'].includes(task.status.id)"
                                             :to="getEditLink(task)"
                                             class="dropdown-item btn btn-secondary shadow btn btn-sm me-1">
                                    <h4><b>{{task.name}}</b></h4>
                                </router-link>
                            </template>
                        </div>
                        <!--end::Title-->
                    </div>
                    <!--end::Card title-->
                    <div class="card-toolbar m-0 text-secondary">
                        № {{task.id}}
                    </div>
                </div>
                <div class="card-body d-flex flex-column px-9 pt-1 pb-8">
                    <!--begin::Heading-->
                    <div class="row">
                        <p class="text-break">
                            {{task.description}}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 p-1" v-if="tasks && tasks.length > 0">
            <PaginationTemplate :per-page="perPage" :count="pagesCount" :current-page="currentPage"></PaginationTemplate>
        </div>

        <div v-if="tasks?.length == 0" class="alert alert-primary">Пока задач нет, их скоро назначит преподаватель</div>
    </div>
</template>

<script>

import ApiService from "@/core/services/ApiService";
import GetSvgByStatus from "./_helpers/GetSvgByStatus.vue";
import PaginationTemplate from "@/components/table/PaginationTemplate.vue";

export default{
    name: "ClientTaskList",
    components: {
        GetSvgByStatus,
        PaginationTemplate
    },
    init() {
    },

    methods: {
        getEditLink(task) {
            return '/task/solve-' + task.type.id + '/'+ task.id;
        },
        getResultLink(task) {
            return '/task/solve-' + task.type.id + '-result/'+ task.id;
        },
        async loadData() {
            let filter  = {
            };
            this.taskStatus ? filter[this.taskStatus] = true : filter[this.taskStatus];
            await ApiService.query('/client/task/list', {
                params: {
                    filter: filter,
                    page: this.currentPage,
                    count: this.perPage,
                }
            }).then((response) => {
                this.tasks = response.data.data.items;
                this.pagesCount = response.data.data.meta.pages_count;
            })
        },
        async setStatus(status) {
            this.taskStatus = status;
            this.currentPage = 1;
            await this.loadData();
        },
    },
    data() {
        return {
            tableData: [],
            search: [],
            selectedIds: [],
            deleteFewCustomers: [],
            sort: [],
            tasks: null,
            currentPage: 1,
            perPage: 10,
            pagesCount: null,
            taskStatus: 'all',
            taskStatuses: {
                'new': {
                    title: 'Новые',
                    errorMessage: 'Нет новых задач',
                },
                '10day': {
                    title: 'За 10 дней',
                    errorMessage: 'Нет задач за последние 10 дней',
                },
                'all': {
                    title: 'Все',
                    errorMessage: 'Нет задач',
                },
            },
        }
    },
    beforeMount() {
        this.loadData();
    },
    mounted() {
        this.emitter.on("change-page", (page) => {
            this.currentPage = page;
            this.loadData();
        });
        this.emitter.on("change-count", (count) => {
            this.perPage = count;
            this.currentPage = 1;
            this.loadData();
        });
    },
    unmounted() {
        this.emitter.off("change-page");
        this.emitter.off("change-count");
    }
};
</script>
