<template>
    <div class="accordion" :id="'kt_accordion_'+item.id">
        <div v-if="item && item.name" class="accordion-item">
            <h2 class="accordion-header" :id="'kt_accordion_'+item.id+'_header_'+item.id"  @mouseover="isHovering = true"
                @mouseout="isHovering = false" >
                <div class="node-tree-button fs-4 fw-semibold p-0" type="button"
                        :data-bs-target="'#kt_accordion_'+item.id+'_body_'+item.id">
                    <div v-if="item.children && item.children.length > 0" v-on:click="hideChildren()">
                        <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Navigation/Angle-down.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                    <path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999) "/>
                                </g>
                            </svg>
                        </span>
                    </div>
                    <div>{{ item.name }} </div>
                    <svg v-on:click="addNode()" :style="{visibility: isHovering ? 'visible' : 'hidden'}" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.3" d="M10 4H21C21.6 4 22 4.4 22 5V7H10V4Z" fill="currentColor"/>
                        <path d="M10.4 3.60001L12 6H21C21.6 6 22 6.4 22 7V19C22 19.6 21.6 20 21 20H3C2.4 20 2 19.6 2 19V4C2 3.4 2.4 3 3 3H9.2C9.7 3 10.2 3.20001 10.4 3.60001ZM16 12H13V9C13 8.4 12.6 8 12 8C11.4 8 11 8.4 11 9V12H8C7.4 12 7 12.4 7 13C7 13.6 7.4 14 8 14H11V17C11 17.6 11.4 18 12 18C12.6 18 13 17.6 13 17V14H16C16.6 14 17 13.6 17 13C17 12.4 16.6 12 16 12Z" fill="currentColor"/>
                        <path opacity="0.3" d="M11 14H8C7.4 14 7 13.6 7 13C7 12.4 7.4 12 8 12H11V14ZM16 12H13V14H16C16.6 14 17 13.6 17 13C17 12.4 16.6 12 16 12Z" fill="currentColor"/>
                    </svg>

                    <svg :style="{visibility: isHovering ? 'visible' : 'hidden'}" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"/>
                        <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"/>
                    </svg>

                    <svg v-on:click="deleteNode()" :style="{visibility: isHovering ? 'visible' : 'hidden'}" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.3" d="M12 10.6L14.8 7.8C15.2 7.4 15.8 7.4 16.2 7.8C16.6 8.2 16.6 8.80002 16.2 9.20002L13.4 12L12 10.6ZM10.6 12L7.8 14.8C7.4 15.2 7.4 15.8 7.8 16.2C8 16.4 8.3 16.5 8.5 16.5C8.7 16.5 8.99999 16.4 9.19999 16.2L12 13.4L10.6 12Z" fill="currentColor"/>
                        <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM13.4 12L16.2 9.20001C16.6 8.80001 16.6 8.19999 16.2 7.79999C15.8 7.39999 15.2 7.39999 14.8 7.79999L12 10.6L9.2 7.79999C8.8 7.39999 8.2 7.39999 7.8 7.79999C7.4 8.19999 7.4 8.80001 7.8 9.20001L10.6 12L7.8 14.8C7.4 15.2 7.4 15.8 7.8 16.2C8 16.4 8.3 16.5 8.5 16.5C8.7 16.5 9 16.4 9.2 16.2L12 13.4L14.8 16.2C15 16.4 15.3 16.5 15.5 16.5C15.7 16.5 16 16.4 16.2 16.2C16.6 15.8 16.6 15.2 16.2 14.8L13.4 12Z" fill="currentColor"/>
                    </svg>
                </div>
            </h2>
        </div>
        <template v-if="item.children && showChildren" v-for="(newItem, index) of item.children">
            <div :id="'kt_accordion_'+item.id+'_body_'+item.id"
                 class="accordion-collapse collapse show"
                 aria-labelledby="kt_accordion_1_header_1"
                 :data-bs-parent="'#kt_accordion_'+item.id">
                <div class="node-tree-child">
                    <node-tree :item="newItem" :index="index">

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
        'item',
        'index'
    ],
    components: {},
    init() {
    },

    methods: {
        addNode() {
            if (!this.item.children) {
                this.item.children = [];
            }
            this.item.children.push({
                id: null,
                name: 'Новая папка',
                parent_id: this.item.id
            });
        },
        deleteNode() {
            console.log(this.item);
            console.log(this.$parent.deleteChildren(this.index));
        },
        deleteChildren(id) {
            let arr = this.item.children.filter(function(item, index) {
                return index !== id
            });
            this.item.children = arr;
        },
        hideChildren() {
            this.showChildren = !this.showChildren;
        }
    },
    data() {
        return {
            showChildren: true,
            isHovering: false,
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
.hovering{
     visibility: visible;
 }
.node-tree-button {
    display: flex;
    border-top-left-radius: var(--bs-accordion-inner-border-radius);
    border-top-right-radius: var(--bs-accordion-inner-border-radius);
    color: var(--bs-accordion-active-color);
    background-color: var(--bs-accordion-active-bg);
    box-shadow: inset 0 calc(-1 * var(--bs-accordion-border-width)) 0 var(--bs-accordion-border-color);
    border: 0;
    border-radius: 0;
    overflow-anchor: none;
    height:30px;
}
.create-folder-body {
    width:300px;
}
</style>
