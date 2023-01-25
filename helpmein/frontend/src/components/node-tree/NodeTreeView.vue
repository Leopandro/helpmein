<template>
    <div class="accordion">
        <div ref="accordion-item" :id="'kt_accordion_'+item.id" v-if="item && item.name && item.parent_id" class="accordion-item">
            <h2 class="accordion-header" :id="'kt_accordion_'+item.id+'_header_'+item.id"  @mouseover="isHovering = true"
                @mouseout="isHovering = false" >
                <div class="node-item-wrapper notice d-flex node-item-primary rounded border p-2" type="button"
                     v-on:click="folderClick(item)"
                     :class="{'node-item-selected': item?.id === store?.selectedNode?.id}"
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
                         class="node-tree-folder-name p-1 h6 d-flex aligns-items-center m-0"
                         :style="{display: showEditInput ? 'none' : 'block'}"
                         :id="'node_item_'+item.id+'_input'">
                        <div>{{ item.name }}</div>
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
                    <NodeTreeView ref="node-tree-item-child" :item="newItem" :index="index" :name="newItem.name">

                    </NodeTreeView>
                </div>
            </div>
        </template>
    </div>
</template>
<script>
import ApiService from "@/core/services/ApiService";
import {useAuthStore} from "@/stores/auth";
import {useNodeStore} from "../../stores/node-tree";
export default {
    name: "NodeTreeView",
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
    data() {
        return {
            selectedItem: null,
            showEditInput: false,
            isHovering: false,
            showChildrenFlagProperty: true,
            task_categories: []
        }
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
        folderClick(item) {
            this.store.selectedNode = item;
            this.emitter.emit("pick-folder", item);
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
        if (this.store.selectedNode.id === this.item.id) {
            this.folderClick(this.item);
        }
    },
    setup() {
        let store = useNodeStore();
        return {
            store
        }
    }
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
.node-tree-folder-name {
    color: black;
}
.node-item-primary {
    background-color: var(--kt-primary-light);
}
.node-item-selected {
    background-color: blanchedalmond;
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
