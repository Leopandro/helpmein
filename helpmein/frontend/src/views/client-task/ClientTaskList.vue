<template>
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                  <span class="svg-icon svg-icon-1 position-absolute ms-6">
                    <inline-svg src="/media/icons/duotune/general/gen021.svg"/>
                  </span>
                </div>
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
                        <th class="min-w-125px">Статус</th>
                        <th class="min-w-125px">Название</th>
                        <th class="min-w-125px">Описание</th>
                        <th class="min-w-125px">Тип задачи</th>
                        <th class="min-w-125px text-end">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="task in tasks">
                        <td>
                            <GetSvgByStatus :status="task.status.id"></GetSvgByStatus>
                        </td>
                        <td>
                            <router-link :to="'/task/solve/' + task.id" class="text-gray-800 text-hover-primary mb-1">
                                {{task.name}}
                            </router-link>
                        </td>
                        <td class="text-dark text-gray-800">{{task.description}}</td>
                        <td class="text-dark text-gray-800">{{task.type.title}}</td>
                        <td class="text-end">

                            <div class="dropdown">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    Действия
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li>
                                        <router-link :to="'/task/solve/'+task.id" class="dropdown-item">
                                            Просмотр
                                        </router-link>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div v-if="tasks?.length == 0" class="alert alert-primary">Пока задач нет, их скоро назначит преподаватель</div>
        </div>
    </div>
</template>

<script>

import Datatable from "@/components/kt-datatable/KTDataTable.vue";
import ExportCustomerModal from "@/components/modals/forms/ExportCustomerModal.vue";
import AddCustomerModal from "@/components/modals/forms/AddCustomerModal.vue";
import ApiService from "../../core/services/ApiService";
import GetSvgByStatus from "./_helpers/GetSvgByStatus.vue";

export default{
    name: "ClientTaskList",
    components: {
        Datatable,
        ExportCustomerModal,
        AddCustomerModal,
        GetSvgByStatus
    },
    init() {
    },

    methods: {
        async searchItems() {
            await ApiService.query('/client/task/list', {
                params: {
                    page: this.currentPage,
                    count: 10
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
            tasks: null
        }
    },
    beforeMount() {
        this.searchItems();
    },
    async mounted() {
    },
};
</script>
