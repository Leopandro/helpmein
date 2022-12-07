<template>
    <div class="card">
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                  <span class="svg-icon svg-icon-1 position-absolute ms-6">
                    <inline-svg src="/media/icons/duotune/general/gen021.svg"/>
                  </span>
                    <input
                        type="text"
                        v-model="search"
                        @input="searchItems()"
                        class="form-control form-control-solid w-250px ps-15"
                        placeholder="Search Users"
                    />
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div
                    class="d-flex justify-content-end"
                    data-kt-customer-table-toolbar="base"
                >
                    <router-link to="create">
                        <button
                            type="button"
                            class="btn btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#kt_modal_add_customer"
                        >
                        <span class="svg-icon svg-icon-2">
                          <inline-svg src="/media/icons/duotune/arrows/arr075.svg"/>
                        </span>
                            Add User
                        </button>
                    </router-link>

                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <table id="kt_datatable_zero_configuration" class="table table-row-bordered gy-5">
                <thead>
                <tr class="fw-semibold fs-6 text-muted">
                    <th>Username</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="user of users">
                    <td>{{ user.login }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script lang="ts">
import {defineComponent, ref, onMounted} from "vue";
import Datatable from "@/components/kt-datatable/KTDataTable.vue";
import type {Sort} from "@/components/kt-datatable//table-partials/models";
import ExportCustomerModal from "@/components/modals/forms/ExportCustomerModal.vue";
import AddCustomerModal from "@/components/modals/forms/AddCustomerModal.vue";
import type {ICustomer} from "@/core/data/customers";
import arraySort from "array-sort";
import ApiService from "@/core/services/ApiService";

export default defineComponent({
    name: "user-list",
    components: {
        Datatable,
        ExportCustomerModal,
        AddCustomerModal,
    },
    init() {
    },

    methods: {
        deleteCustomer(id: number) {

        },
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
        console.log('mounted');
        await ApiService.get('/user/list').then((data: any) => {
            this.users = data.data;
        })
    },
});
</script>
