<template>
    <div v-if="task_category.id">
        <div class="row">

        </div>

        <div class="col-8">
            <div class="row">
                <div class="col-4">
                    <select
                        class="form-select form-select-dark"
                        v-model="selectedUser"
                        @change="loadData">
                        <option :value="client.id" v-for="client in clients">
                            {{client.name}}
                        </option>
                    </select>
                    <select
                        class="form-select form-select-dark"
                        v-model="difficultLevel"
                        @change="loadData">
                        <option value="">
                            -- Нет ---
                        </option>
                        <option :value="levels.id" v-for="levels in difficultLevels">
                            {{levels.name}}
                        </option>
                    </select>
                </div>
                <div class="col-auto ms-auto">
                    <button type="button" class="btn btn-secondary" v-on:click="acceptAssignments">Применить назначение</button>
                </div>
            </div>
        </div>
        <div class="col-8 p-1" v-for="(task, name, index) in tasks">
            <div class="card h-100">

                <div class="card-header flex-nowrap border-0 pt-1">
                    <!--begin::Card title-->
                    <div class="card-title m-0">
                        <div class="row">
                            <div class="p-1 col-auto">
                                <div class="form-check form-check-custom form-check-success form-check-solid">
                                    <input
                                        class="form-check-input"
                                        :id="'task_assign_'+task.id"
                                        type="checkbox"
                                        :checked="['assigned', 'in_review', 'finished', 'reassigned'].includes(task.status.id)"
                                        :disabled="['in_review', 'finished', 'reassigned'].includes(task.status.id)"
                                        v-model="selectedItems[task.id]"/>
                                </div>
                            </div>
                                <div class="p-1 col-auto">
                                    <label :for="'task_assign_'+task.id" role="button">
                                        {{task.type.title}}
                                    </label>
                                </div>
                                <div class="p-1 col-auto">
                                    <label :for="'task_assign_'+task.id" role="button">
                                        {{task.difficult_level}}
                                    </label>
                                </div>
                        </div>
                        <!--end::Title-->
                    </div>
                    <!--end::Card title-->
                    <div class="card-toolbar m-0">
                        № {{task.id}}
                    </div>
                </div>
                <div class="card-body d-flex flex-column px-9 pt-1 pb-8">
                    <!--begin::Heading-->
                    <div class="row">
                        <p class="text-break">
                        <b>{{task.name}}</b>
                        {{task.description}}</p>
                    </div>
                    <div class="row">
                        <p class="text-break">
                            <b>Комментарий для клиента:</b> {{task.comment_client}}
                        </p>
                    </div>
                    <div class="row">
                        <p class="text-break">
                            <b>Комментарий для себя:</b> {{task.comment}}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="tasks.length < 2" class="col-8 p-1" style="min-height: 203px;">
            <div class="card h-100"></div>
        </div>
        <div class="col-8 p-0" v-if="tasks.length > 0">
            <PaginationTemplate :count="pagesCount" :current-page="currentPage" :per-page="perPage"></PaginationTemplate>
        </div>
    </div>
</template>
<script type="ts">
import ApiService from "@/core/services/ApiService";
import PaginationTemplate from "@/components/table/PaginationTemplate.vue";
import Swal from "sweetalert2";
export default {
    name: 'TaskAssignList',
    components: {
        PaginationTemplate
    },
    data() {
        return {
            currentPage: 1,
            perPage: 2,
            pagesCount: null,
            task_category: '',
            tasks: [],
            selectedUser: null,
            difficultLevel: '',
            difficultLevels: [
                {
                    'id': 'A1',
                    'name': 'A1'
                },{
                    'id': 'A2',
                    'name': 'A2'
                },{
                    'id': 'B1',
                    'name': 'B1'
                },{
                    'id': 'B2',
                    'name': 'B2'
                },
            ],
            clients: [],
            selectedItems: {},
        };
    },
    methods: {
        async loadUsers() {
            await ApiService.query('/user/list').then((response) => {
                this.clients = response.data.data.items
                this.selectedUser = this.clients[0].id
                console.log(response);
            })
        },
        async uploadMassAssignment() {
            await ApiService.post('/admin/user-task/mass-assign', {
                selected_items: this.selectedItems,
                selected_user: this.selectedUser,
            }).then((response) => {
                Swal.fire({
                    text: "Успешно!",
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ок!",
                    heightAuto: false,
                    customClass: {
                        confirmButton: "btn btn-primary",
                    },
                }).then(() => {
                    this.loadData();
                    this.selectedItems = {};
                });
            }).catch((response) => {
                Swal.fire({
                    text: "Ошибка!",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ок!",
                    heightAuto: false,
                    customClass: {
                        confirmButton: "btn btn-danger",
                    },
                }).then(async () => {
                    await this.loadData();
                    this.selectedItems = {};
                });
            })
        },
        acceptAssignments() {
            this.uploadMassAssignment();
        },
        async loadData() {
            await ApiService.query('/admin/user-task/list-with-assign', {
                params: {
                    filter: {
                        user_id: this.selectedUser,
                        difficult_level: this.difficultLevel,
                        task_category_id: this.task_category.id
                    },
                    page: this.currentPage,
                    count: this.perPage
                }
            }).then((response) => {
                this.tasks = response.data.data.items;
                this.pagesCount = response.data.data.meta.pages_count;
            })
        },
    },
    mounted() {
        this.loadUsers();
        this.emitter.on("pick-folder", (item) => {
            this.task_category = item;
            this.loadData();
        });
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
        this.emitter.off("pick-folder");
        this.emitter.off("change-page");
    }
}
</script>
<style>
.ml-auto {
    margin-left: auto;
}
</style>
