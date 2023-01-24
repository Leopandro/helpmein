<template>
    <div class="container-fluid row">
        <div class="col-6 p-0">
            <NodeTreeView :item="this.task_categories" :value="this.task_categories.name"></NodeTreeView>
        </div>
        <div class="col-6">
            <TaskAssignList></TaskAssignList>
        </div>
    </div>
</template>

<script>
import ApiService from "@/core/services/ApiService";
import NodeTreeView from "@/components/node-tree/NodeTreeView.vue";
import TaskAssignList from "./TaskAssignList.vue";

export default {
    name: "TaskCategoryAssign",
    components: {
        NodeTreeView,
        TaskAssignList
    },
    init() {
    },
    data() {
        return {
            folder_id: '',
            tableData: [],
            search: [],
            selectedIds: [],
            deleteFewCustomers: [],
            sort: [],
            task_categories: []
        }
    },
    methods: {
        async getItems() {
            await ApiService.get('/category-tree/list').then((data) => {
                this.task_categories = data.data;
            })
        },
        async moveElement(object) {
            await ApiService.post('/category-tree/replace', object).then(() => {
            })
            await ApiService.get('/category-tree/list').then((data) => {
                this.task_categories = data.data;
            })
        },
    },
    async mounted() {
        await this.getItems();
    },
};
</script>
<style>
.line-top{
    border-top: 1px solid red;
    padding-top: 20px;
}
.line-bottom{
    border-bottom: 1px solid red;
    padding-bottom: 20px;
}
</style>
