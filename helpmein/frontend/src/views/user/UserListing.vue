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
                        v-debounce:400="searchItems"
                        class="form-control form-control-solid w-250px ps-15"
                        placeholder="Поиск клиентов"
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
                            Добавить клиента
                        </button>
                    </router-link>

                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <table id="kt_datatable_zero_configuration" class="table table-row-bordered gy-5">
                <thead>
                <tr class="fw-semibold fs-6 text-muted">
                    <th>Имя</th>
                    <th>Фамилия</th>
                    <th>E-mail</th>
                    <th>-</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="user of users">
                    <td>{{ user.name }}</td>
                    <td>{{ user.surname }}</td>
                    <td>{{ user.email }}</td>
                    <td>
                        <router-link :to="'edit/'+user.id">
                            <a href="javascript:;" class="btn btn-icon-primary btn-text-primary p-0"><i class="bi bi-pencil-square fs-4 me-2"></i></a>
                        </router-link>
                        <a href="javascript:;" class="btn btn-icon-danger btn-text-danger p-0"><i class="bi bi-backspace fs-4 me-2"></i></a>
                    </td>
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
import {vue3Debounce} from "vue-debounce";

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
        async searchItems() {
            await ApiService.get('/user/list','?search=' + this.search).then((data: any) => {
                this.users = data.data;
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
