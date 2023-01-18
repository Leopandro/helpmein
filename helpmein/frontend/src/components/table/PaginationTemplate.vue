<template>
    <div class="card h-100">
        <div class="card-body">
            <div class="row">
                <div class="col-8 col-md-7 d-flex align-items-center justify-content-center justify-content-md-start">
                    Страницы:
                    <ul class="pagination pagination-outline">
                        <li class="page-item m-1"
                            v-for="(item, index) in count"
                            :class="{active: currentPage == item}"
                            :value="currentPage"
                            v-on:click="setCurrent(item)"
                        >
                            <a href="javascript:;" class="page-link">{{ item }}</a>
                        </li>
                    </ul>
                </div>
                <div class="col-auto ms-auto d-flex align-items-center">
                    <div class="col-auto">Кол-во:</div>
                    <select class="form-select form-select-sm form-select-solid m-1"
                            :value="perPage"
                            @change="setCurrentCount($event)">
                        <option
                            :value="pages"
                            v-for="pages in pagesCount"
                        >{{pages}}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'PaginationTemplate',
    props: [
        'count',
        'perPage',
        'currentPage',
        'values'
    ],
    data() {
        return {
            pagesCount: [
                10,
                20,
                50
            ]
        };
    },
    methods: {
        setCurrent(index) {
            this.emitter.emit("change-page", index);
        },
        setCurrentCount(event) {
            this.emitter.emit("change-count", event.target.value);
        }
    },
    updated() {
    },
    mounted() {
        if (this.values) {
            this.pagesCount = this.values;
        }
    }
}
</script>
<style>

</style>
