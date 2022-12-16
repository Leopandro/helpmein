<template>
    <div class="accordion" :id="'kt_accordion_'+item.id">
        <div v-if="item && item.name" class="accordion-item">
            <h2 class="accordion-header" :id="'kt_accordion_'+item.id+'_header_'+item.id">
                <button class="accordion-button fs-4 fw-semibold p-0" type="button"
                        :data-bs-target="'#kt_accordion_'+item.id+'_body_'+item.id">
                    {{ item.name }}
                    <div v-on:click="addNode()">
                        <span class="svg-icon svg-icon-muted svg-icon-2hx">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor"/>
                                <rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor"/>
                            </svg>
                        </span>
                    </div>
                </button>

            </h2>
        </div>
        <template v-if="item.children" v-for="newItem of item.children">
            <div :id="'kt_accordion_'+item.id+'_body_'+item.id"
                 class="accordion-collapse collapse show"
                 aria-labelledby="kt_accordion_1_header_1"
                 :data-bs-parent="'#kt_accordion_'+item.id">
                <div class="node-tree-child">
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
    components: {},
    init() {
    },

    methods: {
        addNode() {
            console.log(1);
        }
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
    width: 100%;
    background-color: #eff2f5;
}
.node-tree-child {
    padding-left: 30px;
}
.create-folder-body {
    width:300px;
}
</style>
