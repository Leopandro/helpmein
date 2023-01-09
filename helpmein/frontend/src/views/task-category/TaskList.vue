<template>
    <div v-if="task_category.id">
        <div class="row">

        </div>

        <div class="col-8">
            <router-link :to="getCreateLink()">
                <button type="button" class="btn btn-primary">Добавить задачу</button>
            </router-link>
        </div>
        <div class="col-8 p-1" v-for="(task, name, index) in task_categories">
            <div class="card h-100">

                <div class="card-header flex-nowrap border-0 pt-1">
                    <!--begin::Card title-->
                    <div class="card-title m-0">
                        <!--begin::Icon-->
                        <span class="svg-icon svg-icon-muted svg-icon-2hx" v-on:click="deleteTask(task)" role="button">
                            <svg width="24" height="24" viewBox="0 0 24 24"  xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="#E7505A"/>
                            <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="#E7505A"/>
                            <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="#E7505A"/>
                            </svg>
                        </span>
                        <!--end::Icon-->
                        <!--begin::Title-->
                        <router-link :to="getEditLink(task)">
                            <span class="svg-icon svg-icon-muted svg-icon-2hx">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"></path><path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"></path></svg>
                            </span>
                        </router-link>
                        <div class="p-1">
                            {{task.type.title}}
                        </div>
                        <div class="p-1">
                            {{task.difficult_level}}
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

        <div v-if="task_categories.length < 2" class="col-8 p-1" style="min-height: 203px;">
            <div class="card h-100"></div>
        </div>
        <div class="col-8 p-0" v-if="task_categories.length > 0">
            <PaginationTemplate :per-page="perPage" :count="pagesCount" :current-page="currentPage"></PaginationTemplate>
        </div>
    </div>
</template>
<script type="ts">
import ApiService from "@/core/services/ApiService";
import PaginationTemplate from "@/components/table/PaginationTemplate.vue";
export default {
    name: 'TaskList',
    components: {
        PaginationTemplate
    },
    data() {
        return {
            currentPage: 1,
            perPage: 2,
            pagesCount: null,
            task_category: '',
            task_categories: []
        };
    },
    methods: {
        async loadData() {
            await ApiService.query('/admin/user-task/list', {
                params: {
                    filter: {
                        task_category_id: this.task_category.id
                    },
                    page: this.currentPage,
                    count: this.perPage
                }
            }).then((response) => {
                this.task_categories = response.data.data.items;
                console.log(this.task_categories)
                this.pagesCount = response.data.data.meta.pages_count;
            })
        },
        getCreateLink() {
            return '/task/edit' + '?task_category_id=' + this.task_category.id;
        },
        async deleteTask(item) {
            if (confirm('Удалить задачу?')) {
                await ApiService.post('/task/delete/'+item.id).then((response) => {
                    this.loadData();
                })
            }
        },

        getEditLink(task) {
            return '/task/edit/' + task.id;
        }
    },
    mounted() {
        this.emitter.on("pick-folder", (item) => {
            this.task_category = item;
            this.loadData();
        });
        this.emitter.on("change-page", (page) => {
            this.currentPage = page;
            this.loadData();
        });
        this.emitter.on("change-count", (count) => {
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

</style>
