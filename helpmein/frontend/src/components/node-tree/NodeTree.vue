<template>
    <div class="accordion" :id="'kt_accordion_'+item.id">
        <div v-if="item && item.name" class="accordion-item">
            <h2 class="accordion-header" :id="'kt_accordion_'+item.id+'_header_'+item.id">
                <button class="accordion-button fs-4 fw-semibold" type="button" data-bs-toggle="collapse"
                        :data-bs-target="'#kt_accordion_'+item.id+'_body_'+item.id"
                        aria-expanded="true"
                        :aria-controls="'kt_accordion_'+item.id+'_body_'+item.id">
                    {{ item.name }}
                </button>
            </h2>
        </div>
        <template v-if="item.children" v-for="newItem of item.children">
            <div :id="'kt_accordion_'+item.id+'_body_'+item.id"
                 class="accordion-collapse collapse show"
                 aria-labelledby="kt_accordion_1_header_1"
                 :data-bs-parent="'#kt_accordion_'+item.id">
                <div class="accordion-body">
                        <node-tree :item="newItem">

                        </node-tree>
                </div>
            </div>
        </template>
    </div>
</template>
<script>
import ApiService from "@/core/services/ApiService";
export default {
    name: "NodeTree",
    props: [
        'item'
    ],
    components: {
    },
    init() {
    },

    methods: {
    },
    data() {
        return {
            task_categories: []
        }
    },
    async mounted() {
        console.log(this.item);
    },
};
</script>
<style>
.accordion-item {
    width: 300px !important;
}
.accordion-body {
    padding-bottom: 0px;
    padding-top: 0px;
}
</style>
