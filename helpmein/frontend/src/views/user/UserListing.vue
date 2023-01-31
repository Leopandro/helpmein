<template>
    <div class="card">
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    <div class="form-check p-0">
                        <input
                            id="filter_active"
                            type="checkbox"
                            v-debounce:400="searchItems"
                            v-on:change="searchItems"
                            v-model="filter.active">
                        <label class="form-check-label p-1" for="filter_active">
                            {{$t('Показывать только активных клиентов')}}
                        </label>
                    </div>
                    <div>
                    </div>
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
                            {{$t('Добавить клиента')}}
                        </button>
                    </router-link>

                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <table id="kt_datatable_zero_configuration" class="table table-row-bordered gy-5">
                <thead>
                <tr class="fw-semibold fs-6 text-muted">
                    <th></th>
                    <th>{{$t('Фамилия Имя')}}</th>
                    <th>{{$t('На проверке')}}</th>
                    <th>{{$t('Активные')}}</th>
                    <th>{{$t('За 3 дня')}}</th>
                    <th>{{$t('За 10 дней')}}</th>
                    <th>-</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="user of users">
                    <td>
                        <span v-if="user.active" class="svg-icon svg-icon-primary svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 30 30" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 20 0 20 20 0 20"/>
                                <path d="M6.26193932,17.6476484 C5.90425297,18.0684559 5.27315905,18.1196257 4.85235158,17.7619393 C4.43154411,17.404253 4.38037434,16.773159 4.73806068,16.3523516 L13.2380607,6.35235158 C13.6013618,5.92493855 14.2451015,5.87991302 14.6643638,6.25259068 L19.1643638,10.2525907 C19.5771466,10.6195087 19.6143273,11.2515811 19.2474093,11.6643638 C18.8804913,12.0771466 18.2484189,12.1143273 17.8356362,11.7474093 L14.0997854,8.42665306 L6.26193932,17.6476484 Z" fill="#000000" fill-rule="nonzero" transform="translate(11.999995, 12.000002) rotate(-180.000000) translate(-11.999995, -12.000002) "/>
                            </g>
                        </svg><!--end::Svg Icon--></span>
                    </td>
                    <td>
                        <router-link :to="'/user/edit/'+user.id+'/task/'">
                            {{ user.surname + ' ' + user.name }}
                        </router-link>
                    </td>
                    <td>{{user.taskStats.in_review}}</td>
                    <td>{{user.taskStats.active}}</td>
                    <td>{{user.taskStats.finished}}</td>
                    <td>{{user.taskStats.finished10}}</td>
                    <td>
                        <router-link :to="'edit/'+user.id">
                            <a href="javascript:;" class="btn btn-icon-primary btn-text-primary p-0"><i class="bi bi-pencil-square fs-4 me-2"></i></a>
                        </router-link>
                        <a v-on:click="deleteUser(user)" href="javascript:;" class="btn btn-icon-danger btn-text-danger p-0"><i class="bi bi-backspace fs-4 me-2"></i></a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script lang="ts">
import {defineComponent, ref, onMounted} from "vue";
import ApiService from "@/core/services/ApiService";

export default defineComponent({
    name: "user-list",
    init() {
    },

    methods: {
        async searchItems() {
            await ApiService.query('/user/list',
                {
                    params: {
                        filter: {
                            active: this.filter.active
                        },
                        search: this.filter.search,
                        page: this.currentPage,
                        count: 10
                    }
                }).then((response) => {
                this.users = response.data.data.items;
            })
        },
        async deleteUser(user) {
            if (confirm("Вы уверены")) {
                await ApiService.post('/user/delete/'+user.id).then((data: any) => {
                    this.searchItems();
                })
            }
        }
    },
    data() {
        return {
            currentPage: 1,
            perPage: 10,
            pagesCount: null,
            tableData: [],
            filter: {
                active: true,
                search: '',
            },
            selectedIds: [],
            deleteFewCustomers: [],
            sort: [],
            users: []
        }
    },
    async mounted() {
        await this.searchItems();
    },
});
</script>
<style>
.form-check-label {
    font-weight: 300;
    font-size: 1rem;

}
</style>
