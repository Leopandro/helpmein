<template>
    <div class="card h-100">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-start">
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
                <div class="col-auto ms-auto">
                    <select class="form-select form-select-sm form-select-solid m-1" :value="perPage" @change="setCurrentCount($event)">
                        <option value="2">2</option>
                        <option value="10">10</option>
                        <option value="50">50</option>
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
        'currentPage'
    ],
    methods: {
        setCurrent(index) {
            this.emitter.emit("change-page", index);
        },
        setCurrentCount(event) {
            this.emitter.emit("change-count", event.target.value);
        }
    },
    updated() {
        console.log(this);
    }
}
</script>
<style>

</style>
