<template>
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <button :class="{'btn-default': taskStatus === null}" v-on:click="setStatus(null)" type="button" class="btn btn-sm btn-light">Все задачи</button>
                <button :class="{'btn-default': taskStatus === 'in_progress'}" v-on:click="setStatus('in_progress')" type="button" class="btn btn-sm btn-light">В работе</button>
                <button :class="{'btn-default': taskStatus === 'in_review'}" v-on:click="setStatus('in_review')" type="button" class="btn btn-sm btn-light">На проверку</button>
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
            <div v-if="tasks?.length == 0" class="alert alert-primary">Пока задачи не назначены</div>
        </div>
    </div>
</template>

<script>
import Datatable from "@/components/kt-datatable/KTDataTable.vue";
import ExportCustomerModal from "@/components/modals/forms/ExportCustomerModal.vue";
import AddCustomerModal from "@/components/modals/forms/AddCustomerModal.vue";
import ApiService from "@/core/services/ApiService";
import GetSvgByStatus from "@/views/client-task/_helpers/GetSvgByStatus.vue";

export default {
    name: "UserTaskList",
    components: {
        Datatable,
        ExportCustomerModal,
        AddCustomerModal,
        GetSvgByStatus
    },
    data() {
        return {
            tableData: [],
            search: [],
            selectedIds: [],
            deleteFewCustomers: [],
            sort: [],
            tasks: null,
            taskStatus: null,
        }
    },
    init() {
    },

    methods: {
        async searchItems() {
            await ApiService.query('/admin/user-task/list', {
                params: {
                    filter: {
                        task_status: this.taskStatus,
                        user_id: this.$route.params.id
                    },
                    page: this.currentPage,
                    count: 10
                }
            }).then((response) => {
                this.tasks = response.data.data.items;
                this.pagesCount = response.data.data.meta.pages_count;
            })
        },
        setStatus(status) {
            this.taskStatus = status;
        },
    },
    beforeMount() {
        this.searchItems();
    },
    async mounted() {
    },
};
</script>
<style>
.form-check-label {
    font-weight: 300;
    font-size: 1rem;

}
</style>
