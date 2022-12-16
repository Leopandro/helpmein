<template>
    <div class="card">
        <div class="container-fluid">
            <div>
                <node-tree :item="this.task_categories"></node-tree>
<!--                <div class="accordion-item">-->
<!--                    <h2 class="accordion-header" id="kt_accordion_1_header_1">-->
<!--                        <div class="create-folder-body p-0">-->
<!--                            <div class="col-6 d-inline-block">-->
<!--                                <input type="email" class="form-control" placeholder="Новая папка"/>-->
<!--                            </div>-->
<!--                            <div class="col-2 d-inline-block">-->
<!--                                    <span class="svg-icon svg-icon-muted svg-icon-2hx"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--                                        <path opacity="0.3" d="M10 18C9.7 18 9.5 17.9 9.3 17.7L2.3 10.7C1.9 10.3 1.9 9.7 2.3 9.3C2.7 8.9 3.29999 8.9 3.69999 9.3L10.7 16.3C11.1 16.7 11.1 17.3 10.7 17.7C10.5 17.9 10.3 18 10 18Z" fill="currentColor"/>-->
<!--                                        <path d="M10 18C9.7 18 9.5 17.9 9.3 17.7C8.9 17.3 8.9 16.7 9.3 16.3L20.3 5.3C20.7 4.9 21.3 4.9 21.7 5.3C22.1 5.7 22.1 6.30002 21.7 6.70002L10.7 17.7C10.5 17.9 10.3 18 10 18Z" fill="currentColor"/>-->
<!--                                        </svg>-->
<!--                                    </span>-->
<!--                            </div>-->
<!--                            <div class="col-2 d-inline-block">-->
<!--                                    <span class="svg-icon svg-icon-muted svg-icon-2hx"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"/>-->
<!--                                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"/>-->
<!--                                    </svg>-->
<!--                                    </span>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </h2>-->
<!--                </div>-->
            </div>
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
