<template>
    <div class="container-fluid">
        <div>
            <node-tree :item="this.task_categories" :value="this.task_categories.name"></node-tree>
        </div>
    </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import ApiService from "@/core/services/ApiService";
import NodeTree from "@/components/node-tree/NodeTree.vue";
export default defineComponent({
    name: "task-listing",
    components: {
        NodeTree
    },
    init() {
    },

    methods: {
        async searchItems() {
            await ApiService.get('/category-tree/list').then((data: any) => {
                this.task_categories = data.data;
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
            task_categories: []
        }
    },
    async mounted() {
        await ApiService.get('/category-tree/list').then((data: any) => {
            this.task_categories = data.data;
        })
    },
});
</script>
