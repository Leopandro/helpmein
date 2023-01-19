<template>
    <div v-if="task_category.id">
        <div class="col-12">
            <div class="row">
                <div class="col-4">
                    <div class="row">
                        <div class="form-floating">
                            <select
                                class="form-select" id="floatingSelect" aria-label="Floating label select example"
                                v-model="selectedUser"
                                @change="changeUser">
                                <option value="">
                                    Выбрать клиента
                                </option>
                                <option :value="client.id" v-for="client in clients">
                                    {{client.name + ' ' + client.surname}}
                                </option>
                            </select>
                            <label for="floatingSelect">Кому</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-floating">
                            <select
                                class="form-select" id="floatingSelect2" aria-label="123123"
                                v-model="difficultLevel"
                                @change="loadData">
                                <option value="">
                                    Все
                                </option>
                                <option :value="levels.id" v-for="levels in difficultLevels">
                                    {{levels.name}}
                                </option>
                            </select>
                            <label for="floatingSelect2">Фильтр по уровню</label>
                        </div>
                    </div>
                </div>
                <div class="col-auto ms-auto">
                    <button type="button" class="btn btn-primary" v-on:click="acceptAssignments">
                        {{getButtonText()}}
                    </button>
                </div>
            </div>
        </div>

        <div style="min-height: 406px;" v-if="tasks.length > 0">
            <div class="col-12 p-1 task-list-item" v-for="(task, name, index) in tasks">
                <div class="card h-100">

                    <div class="card-header flex-nowrap border-0 pt-1">
                        <!--begin::Card title-->
                        <div class="card-title m-0">
                            <div class="col-auto">
                                <div class="p-1 col-auto">
                                    <div class="form-check form-check-custom form-check-success form-check-solid">
                                        <input
                                            class="form-check-input"
                                            :id="'task_assign_'+task.id"
                                            type="checkbox"
                                            :disabled="!(task.status?.id === 'assigned' || task.status?.id === 'not_assigned')"
                                            v-model="selectedItems[task.id]"/>
                                    </div>
                                </div>
                            </div>
                            <label :for="'task_assign_'+task.id" role="button">
                                <span class="p-1 h6">
                                    {{task.type.title}}
                                </span>
                                <span class="p-1 h6">
                                    {{task.difficult_level}}
                                </span>
                            </label>
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
                            <h6><b>{{task.name}}</b></h6>
                            <p class="text-break">
                                {{task.description}}</p>
                        </div>
                        <div class="row" v-if="task.comment_client">
                            <p class="text-break">
                                <b>Комментарий для клиента:</b> {{task.comment_client}}
                            </p>
                        </div>
                        <div class="row" v-if="task.comment">
                            <p class="text-break">
                                <b>Комментарий для себя:</b> {{task.comment}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="tasks.length === 0">
            <div class="alert alert-primary">{{getErrorMessage()}}</div>
        </div>

        <div class="col-12 p-0" v-if="tasks.length > 0">
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
            perPage: 10,
            pagesCount: null,
            loading: null,
            task_category: '',
            tasks: [],
            tasksErrorMessage: '',
            selectedUser: '',
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
            selectedItemsCount: 0,
            selectedItems: {},
        };
    },
    computed: {
        count() {
            var len = 0;

            for (const [key, value] of Object.entries(this.selectedItems)) {
                if (value === true || value === false) {
                    len++;
                }
            }
            return len;
        }
    },
    methods: {
        getErrorMessage() {
            if (this.loading === true) {
                return "Загрузка...";
            }
            if (this.tasks.length === 0) {
                if (this.tasksErrorMessage) {
                    return this.tasksErrorMessage;
                }
                return "Задач нет";
            }
        },
        getButtonText() {
            let message = "";
            if (this.count === 0) {
                message = "Не выбраны задачи для назначения"
            } else {
                message = "Назначить задачи"
            }
            message = "Назначить задачи"
            return message;
        },
        async loadUsers() {
            await ApiService.query('/user/list').then((response) => {
                this.clients = response.data.data.items
                console.log(response);
            })
        },
        async uploadMassAssignment() {
            await ApiService.post('/admin/user-task-tree/mass-assign', {
                selected_items: this.selectedItems,
                selected_user: this.selectedUser,
            }).then((response) => {
                Swal.fire({
                    text: response.data.data.message,
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ок!",
                    heightAuto: false,
                    customClass: {
                        confirmButton: "btn btn-primary",
                    },
                }).then(async () => {
                    await this.loadData();
                });
            }).catch((error) => {
                Swal.fire({
                    text: error.response.data.message,
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ок!",
                    heightAuto: false,
                    customClass: {
                        confirmButton: "btn btn-danger",
                    },
                }).then(async () => {
                    await this.loadData();
                });
            })
        },
        acceptAssignments() {
            this.uploadMassAssignment();
        },
        async changeUser() {
            this.selectedItems = {};
            await this.loadData();
        },
        async loadData() {
            this.loading = true;
            this.tasksErrorMessage = '';
            await ApiService.query('/admin/user-task-tree/list-with-assign', {
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
                this.tasks.forEach((task) => {
                    if (this.selectedItems[task.id] === undefined) {
                        this.selectedItems[task.id] = ['assigned', 'reassigned', 'in_review', 'finished'].includes(task.status.id);
                    }
                });
                this.pagesCount = response.data.data.meta.pages_count;
            }).catch((error) => {
                this.tasks = [];
                this.tasksErrorMessage = error.response.data.message;
            })
            this.loading = false;
        },
    },
    async mounted() {
        await this.loadUsers();
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
    },
    updated() {
        console.log(this.selectedItems);
    }
}
</script>
<style scoped>
.task-list-item {
    max-height: 203px;
    overflow: hidden;
}
.card .card-header {
    min-height: 50px;
    height: 50px;
}
.swal2-html-container {
    white-space: pre-wrap;
}
</style>
