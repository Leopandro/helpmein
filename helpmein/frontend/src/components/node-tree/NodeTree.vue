<template>
    <div class="accordion">
        <div v-if="!item.parent_id" class="box">
            <button class="node-tree-button px-0" v-on:click="addNode()">
                <div class="">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.3" d="M10 4H21C21.6 4 22 4.4 22 5V7H10V4Z" fill="currentColor"/>
                        <path d="M10.4 3.60001L12 6H21C21.6 6 22 6.4 22 7V19C22 19.6 21.6 20 21 20H3C2.4 20 2 19.6 2 19V4C2 3.4 2.4 3 3 3H9.2C9.7 3 10.2 3.20001 10.4 3.60001ZM16 12H13V9C13 8.4 12.6 8 12 8C11.4 8 11 8.4 11 9V12H8C7.4 12 7 12.4 7 13C7 13.6 7.4 14 8 14H11V17C11 17.6 11.4 18 12 18C12.6 18 13 17.6 13 17V14H16C16.6 14 17 13.6 17 13C17 12.4 16.6 12 16 12Z" fill="currentColor"/>
                        <path opacity="0.3" d="M11 14H8C7.4 14 7 13.6 7 13C7 12.4 7.4 12 8 12H11V14ZM16 12H13V14H16C16.6 14 17 13.6 17 13C17 12.4 16.6 12 16 12Z" fill="currentColor"/>
                    </svg>
                </div>
                <div class="node-tree-button-add-text p-1 px-3">
                        Добавить корневую папку
                </div>
            </button>
            <div class="how-to p-1">
                <router-link to="/how-to-node">
                 Как перемещать создавать и редактировать папки
                </router-link>
            </div>
        </div>
        <div ref="accordion-item" :id="'kt_accordion_'+item.id" v-if="item && item.name && item.parent_id" class="accordion-item">
            <h2 class="accordion-header" :id="'kt_accordion_'+item.id+'_header_'+item.id"  @mouseover="isHovering = true"
                @mouseout="isHovering = false" >
                <div class="node-item-wrapper notice d-flex bg-light-primary rounded border p-2" type="button"
                     v-on:click="folderClick(item.id)"
                        :data-bs-target="'#kt_accordion_'+item.id+'_body_'+item.id">
                    <svg class="add-to-folder" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.3" d="M10 4H21C21.6 4 22 4.4 22 5V7H10V4Z" fill="currentColor"/>
                        <path d="M9.2 3H3C2.4 3 2 3.4 2 4V19C2 19.6 2.4 20 3 20H21C21.6 20 22 19.6 22 19V7C22 6.4 21.6 6 21 6H12L10.4 3.60001C10.2 3.20001 9.7 3 9.2 3Z" fill="currentColor"/>
                    </svg>
                    <div class="min-w-10px">
                        <div style="width: 24px" v-if="item.children && item.children.length > 0" v-on:click="toggleChildren()">
                            <span class="svg-icon svg-icon-primary svg-icon-2x">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24"/>
                                        <path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="#909090" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999) "/>
                                    </g>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div ref="div"
                         class="node-tree-folder-name p-1"
                         :style="{display: showEditInput ? 'none' : 'block'}"
                         :id="'node_item_'+item.id+'_input'">
                        {{ item.name }}
                    </div>
                    <input type="text" ref="input" :style="{display: showEditInput ? 'block' : 'none'}"
                           @keydown.enter="saveNode">
                    <div class="pl-1">
                        <svg v-on:click="addNode()" :style="{visibility: isHovering ? 'visible' : 'hidden'}" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path opacity="0.3" d="M10 4H21C21.6 4 22 4.4 22 5V7H10V4Z" fill="currentColor"/>
                            <path d="M10.4 3.60001L12 6H21C21.6 6 22 6.4 22 7V19C22 19.6 21.6 20 21 20H3C2.4 20 2 19.6 2 19V4C2 3.4 2.4 3 3 3H9.2C9.7 3 10.2 3.20001 10.4 3.60001ZM16 12H13V9C13 8.4 12.6 8 12 8C11.4 8 11 8.4 11 9V12H8C7.4 12 7 12.4 7 13C7 13.6 7.4 14 8 14H11V17C11 17.6 11.4 18 12 18C12.6 18 13 17.6 13 17V14H16C16.6 14 17 13.6 17 13C17 12.4 16.6 12 16 12Z" fill="currentColor"/>
                            <path opacity="0.3" d="M11 14H8C7.4 14 7 13.6 7 13C7 12.4 7.4 12 8 12H11V14ZM16 12H13V14H16C16.6 14 17 13.6 17 13C17 12.4 16.6 12 16 12Z" fill="currentColor"/>
                        </svg>
                    </div>

                    <div class="pl-1">
                        <svg v-on:click="editNode()" :style="{visibility: isHovering ? 'visible' : 'hidden'}" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor"/>
                            <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor"/>
                        </svg>
                    </div>

                    <div class="pl-1">
                        <svg v-on:click="deleteNode()" :style="{visibility: isHovering ? 'visible' : 'hidden'}" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path opacity="0.3" d="M12 10.6L14.8 7.8C15.2 7.4 15.8 7.4 16.2 7.8C16.6 8.2 16.6 8.80002 16.2 9.20002L13.4 12L12 10.6ZM10.6 12L7.8 14.8C7.4 15.2 7.4 15.8 7.8 16.2C8 16.4 8.3 16.5 8.5 16.5C8.7 16.5 8.99999 16.4 9.19999 16.2L12 13.4L10.6 12Z" fill="currentColor"/>
                            <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM13.4 12L16.2 9.20001C16.6 8.80001 16.6 8.19999 16.2 7.79999C15.8 7.39999 15.2 7.39999 14.8 7.79999L12 10.6L9.2 7.79999C8.8 7.39999 8.2 7.39999 7.8 7.79999C7.4 8.19999 7.4 8.80001 7.8 9.20001L10.6 12L7.8 14.8C7.4 15.2 7.4 15.8 7.8 16.2C8 16.4 8.3 16.5 8.5 16.5C8.7 16.5 9 16.4 9.2 16.2L12 13.4L14.8 16.2C15 16.4 15.3 16.5 15.5 16.5C15.7 16.5 16 16.4 16.2 16.2C16.6 15.8 16.6 15.2 16.2 14.8L13.4 12Z" fill="#E35B5A"/>
                        </svg>
                    </div>
                </div>
            </h2>
        </div>
        <template v-for="(newItem, index) of item.children">
            <div :style="{display: showChildrenFlag ? 'block' : 'none'}"  :id="'kt_accordion_'+item.id+'_body_'+item.id"
                 class="accordion-collapse collapse show"
                 aria-labelledby="kt_accordion_1_header_1"
                 :data-bs-parent="'#kt_accordion_'+item.id">
                <div class="node-tree-child">
                    <node-tree ref="node-tree-item-child" :item="newItem" :index="index" :name="newItem.name" @change-value="changeValue">

                    </node-tree>
                </div>
            </div>
        </template>
    </div>
</template>
<script>
import ApiService from "@/core/services/ApiService";
import {useAuthStore} from "@/stores/auth";
export default {
    name: "NodeTree",
    props: [
        'item',
        'index',
    ],
    emits: ['update:name'],
    components: {
        'child': this
    },
    init() {
    },
    methods: {
        isVisible() {

        },
        async addNode() {
            const api = ApiService;
            const auth = useAuthStore();
            let openId = null;
            await api.post('/category-tree/add', {
                user_id: auth.user.id,
                id: this.item.id,
                name: 'Новая папка',
            }).then((response) => {
                if (!this.item.children) {
                    this.item.children = [];
                }
                this.item.children.push({
                    id: response.data.id,
                    name: response.data.name,
                    parent_id: response.data.parent_id
                });
                openId = response.data.id;
            });
            this.showChildren(openId);
        },
        async saveNode(event) {
            await this.$emit('change-value', {
                value: event.target.value,
                id: this.item.id
            });

            this.showEditInput = false;
        },
        async updateNode(object) {
            const api = ApiService;
            const auth = useAuthStore();
            await api.post('/category-tree/edit', {
                user_id: auth.user.id,
                id: object.id,
                name: object.name,
            }).then((response) => {
                if (!this.item.children) {
                    this.item.children = [];
                }
                const item = this.item.children.find(element => element.id === response.data.id);
                item.name = response.data.name;
            });
        },
        async editNode() {
            if (this.showEditInput === true) {
                await this.$emit('change-value', {
                    value: this.$refs.input.value,
                    id: this.item.id
                });
                this.showEditInput = !this.showEditInput;
                return;
            }
            this.$refs.input.value = this.item.name;
            this.showEditInput = !this.showEditInput;
            setTimeout(() => {
                this.$refs.input.focus();
            }, 1);

            this.emitter.emit("reload-tree");
        },
        async deleteNode() {
            if (confirm('Удалить папку')) {
                await this.$parent.deleteChildren(this.index);
            }
        },
        async changeValue(data) {
            await this.updateNode({
                name: data.value,
                id: data.id
            });
            let element = this.item.children.find(element => element.id === data.id);
            element.name = data.value;
        },
        async deleteChildren(id) {
            const api = ApiService;
            await api.post('/category-tree/delete', {
                id: this.item.children[id].id,
            }).then((response) => {
                let item = this.item.children.filter(function(item, index) {
                    return index !== id
                });
                this.item.children = item;
            });
        },
        showChildren(id = null) {
            this.showChildrenFlag = true;
            if (id) {
                const nodeItem = this.$refs["node-tree-item-child"].find((nodeItem) => {
                    return nodeItem.item.id === id;
                });
                nodeItem.editNode();
            }
        },
        hideChildren() {
            this.showChildrenFlag = false;
        },
        toggleChildren() {
            this.showChildrenFlag = !this.showChildrenFlag;
        },
        folderClick(id) {
            this.emitter.emit("pick-folder", id);
        }
    },
    data() {
        return {
            showEditInput: false,
            isHovering: false,
            showChildrenFlagProperty: true,
            task_categories: []
        }
    },
    computed: {
        showChildrenFlag: {
            get: function () {
                return this.showChildrenFlagProperty
            },
            set: function (value) {
                if (this.item) {
                    localStorage.setItem('node-item-' + this.item.id, value);
                    this.showChildrenFlagProperty = Boolean(value);
                }
            },
        }
    },
    mounted() {
        if (this.item.parent_id == null) {
            this.showChildrenFlagProperty = true;
        } else {
            if (localStorage.getItem('node-item-' + this.item.id) === 'false') {
                this.showChildrenFlagProperty = false;
                this.showChildrenFlag = false;
            } else {

                this.showChildrenFlagProperty = true;
                this.showChildrenFlag = true;
            }
        }

    },
};
</script>
<style>
.accordion-item {
    width: 100%;
    background-color: #eff2f5;
}
.node-tree-child .node-tree-child {
    padding-left: 30px;
}
.hovering{
     visibility: visible;
 }
.node-tree-folder-name {
    color: black;
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
.pl-1 {
    padding-left: 1rem;
}
.box {
    display: flex;
}
.how-to {
    float: right;
    margin-left: auto;
}
.node-tree-child {
}
.node-item-wrapper:hover {
    --bs-border-opacity: 1 !important;
    padding: 5.5px !important;
    border: 2px dashed #eff2f5!important;
    border-color: var(--kt-border-dashed-color) !important;
}
</style>
