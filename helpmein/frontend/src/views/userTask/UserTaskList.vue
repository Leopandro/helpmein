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
            Пока задач нет, их скоро назначит преподаватель
        </div>
    </div>
</template>

<script>

import Datatable from "@/components/kt-datatable/KTDataTable.vue";
import ExportCustomerModal from "@/components/modals/forms/ExportCustomerModal.vue";
import AddCustomerModal from "@/components/modals/forms/AddCustomerModal.vue";
import ApiService from "@/core/services/ApiService";

export default{
    name: "UserTaskList",
    components: {
        Datatable,
        ExportCustomerModal,
        AddCustomerModal,
    },
    init() {
    },

    methods: {
        async searchItems() {
        }
    },
    data() {
        return {
            tableData: [],
            search: [],
            selectedIds: [],
            deleteFewCustomers: [],
            sort: [],
            users: []
        }
    },
    async mounted() {
    },
};
</script>
