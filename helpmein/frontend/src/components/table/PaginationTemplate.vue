<template>
    <div class="card h-100">
        <div class="card-body">
            <ul class="pagination pagination-outline">
                <li class="page-item m-1"
                    v-for="(item, index) in count"
                    :class="{active: currentPage == item}"
                    v-on:click="setCurrent(item)"
                >
                    <a href="javscript:;" class="page-link">{{ item }}</a>
                </li>
            </ul>
        </div>
    </div>
</template>
<script>
export default {
    name: 'PaginationTemplate',
    props: [
        'count',
        'currentPage'
    ],
    methods: {
        setCurrent(item) {
            this.emitter.emit("change-page", item);
        }
    }
}
</script>
<style>

</style>
