<template>
    <div class="card">
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
<!--                    <input-->
<!--                        type="text"-->
<!--                        v-model="search"-->
<!--                        v-debounce:400="searchItems"-->
<!--                        class="form-control form-control-solid w-250px ps-15"-->
<!--                        placeholder="Search Users"-->
<!--                    />-->
                <!--end::Search-->
            </div>
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div
                    class="d-flex justify-content-end"
                    data-kt-customer-table-toolbar="base"
                >
<!--                    <router-link to="create">-->
<!--                        <button-->
<!--                            type="button"-->
<!--                            class="btn btn-primary"-->
<!--                            data-bs-toggle="modal"-->
<!--                            data-bs-target="#kt_modal_add_customer"-->
<!--                        >-->
<!--                        <span class="svg-icon svg-icon-2">-->
<!--                          <inline-svg src="/media/icons/duotune/arrows/arr075.svg"/>-->
<!--                        </span>-->
<!--                            Add User-->
<!--                        </button>-->
<!--                    </router-link>-->

                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="list-inline-item">
                <node-tree :item="this.task_categories"></node-tree>
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
