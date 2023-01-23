<template>
    <div class="col-lg-8 col-md-10 col-sm-12">
        <table class="table table-row-dashed fs-6 gy-5 my-0" v-if="tasks?.length > 0">
<!--                <thead>-->
<!--                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">-->
<!--                        <th class="min-w-125px">Статус</th>-->
<!--                        <th class="min-w-125px">Название</th>-->
<!--                        <th class="min-w-125px">Описание</th>-->
<!--                        <th class="min-w-125px">Тип задачи</th>-->
<!--                        <th class="min-w-125px text-end">Действия</th>-->
<!--                    </tr>-->
<!--                </thead>-->
<!--                <tbody>-->
<!--                    <tr v-for="task in tasks">-->
<!--                        <td class="text-dark text-gray-800 align-middle">-->
<!--                            <GetSvgByStatus :status="task.status.id"></GetSvgByStatus>-->
<!--                        </td>-->
<!--                        <td class="text-dark text-gray-800 align-middle">-->
<!--                            <router-link :to="'/task/view/' + task.id" class="text-gray-800 text-hover-primary mb-1">-->
<!--                                {{task.name}}-->
<!--                            </router-link>-->
<!--                        </td>-->
<!--                        <td class="text-dark text-gray-800 align-middle">{{task.description}}</td>-->
<!--                        <td class="text-dark text-gray-800 align-middle">{{task.type.title}}</td>-->
<!--                        <td class="text-end">-->

<!--                            <div class="dropdown">-->
<!--                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">-->
<!--                                    Действия-->
<!--                                </button>-->
<!--                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">-->
<!--                                    <li>-->
<!--                                        <template v-if="task.type.id === 'essay'">-->
<!--                                            <router-link v-if="['reassigned','in_review','finished'].includes(task.status.id)" :to="'/task/view/'+task.id" class="dropdown-item">-->
<!--                                                Просмотр ответа-->
<!--                                            </router-link>-->
<!--                                            <router-link v-if="['assigned','reassigned'].includes(task.status.id)" :to="getEditLink(task)" class="dropdown-item">-->
<!--                                                Отправить ответ-->
<!--                                            </router-link>-->
<!--                                        </template>-->
<!--                                        <template v-if="task.type.id === 'task'">-->
<!--                                            <router-link v-if="!['assigned'].includes(task.status.id)" :to="getResultLink(task)" class="dropdown-item">-->
<!--                                                Просмотр решения-->
<!--                                            </router-link>-->
<!--                                            <router-link v-if="['assigned'].includes(task.status.id)" :to="getEditLink(task)" class="dropdown-item">-->
<!--                                                Отправить решение-->
<!--                                            </router-link>-->
<!--                                        </template>-->
<!--                                    </li>-->
<!--                                </ul>-->
<!--                            </div>-->
<!--                        </td>-->
<!--                    </tr>-->
<!--                </tbody>-->
        </table>

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

        <div class="col-12 p-0" v-if="tasks && tasks.length > 0">
            <PaginationTemplate :per-page="perPage" :count="pagesCount" :current-page="currentPage"></PaginationTemplate>
        </div>

        <div v-if="tasks?.length == 0" class="alert alert-primary">Пока задач нет, их скоро назначит преподаватель</div>
    </div>
</template>

<script>

import Datatable from "@/components/kt-datatable/KTDataTable.vue";
import ExportCustomerModal from "@/components/modals/forms/ExportCustomerModal.vue";
import AddCustomerModal from "@/components/modals/forms/AddCustomerModal.vue";
import ApiService from "@/core/services/ApiService";
import GetSvgByStatus from "./_helpers/GetSvgByStatus.vue";
import PaginationTemplate from "@/components/table/PaginationTemplate.vue";

export default{
    name: "ClientTaskList",
    components: {
        Datatable,
        ExportCustomerModal,
        AddCustomerModal,
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
            await ApiService.query('/client/task/list', {
                params: {
                    page: this.currentPage,
                    count: this.perPage
                }
            }).then((response) => {
                this.tasks = response.data.data.items;
                this.pagesCount = response.data.data.meta.pages_count;
            })
        }
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
