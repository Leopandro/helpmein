<template>
    <div class="container-fluid">
        <div>
            <node-tree ref="node-tree" :item="this.task_categories" :value="this.task_categories.name"></node-tree>
        </div>
    </div>
</template>

<script>
import ApiService from "@/core/services/ApiService";
import NodeTree from "@/components/node-tree/NodeTree.vue";

export default {
    name: "TaskListing",
    components: {
        NodeTree
    },
    init() {
    },
    data() {
        return {
            tableData: [],
            search: [],
            selectedIds: [],
            deleteFewCustomers: [],
            sort: [],
            task_categories: []
        }
    },
    methods: {
        async searchItems() {
            await ApiService.get('/category-tree/list').then((data) => {
                this.task_categories = data.data;
            })
        },
        async moveElement(object) {
            await ApiService.post('/category-tree/replace', object).then(() => {
            })
            await ApiService.get('/category-tree/list').then((data) => {
                this.task_categories = data.data;
            })
        },
        loadNodes() {
            const nodeItems = document.getElementsByClassName('accordion-item');
            if (nodeItems) {
                let currentItem = null;
                let fromItem = null;
                let moveTo = null;
                for (let nodeItem of nodeItems) {
                    nodeItem.draggable = true;
                    nodeItem.ondragstart = (event) => {
                        fromItem = event.target;
                        currentItem = event.target;
                    };
                    nodeItem.ondragover = (event) => {
                        let folderTarget = event.target.closest('.add-to-folder');
                        let eventTarget = event.target.closest('.accordion-item');
                        if (folderTarget) {
                            currentItem.classList.remove('line-top');
                            currentItem.classList.remove('line-bottom');
                            moveTo = 'inner';
                        } else if (eventTarget) {

                            let height = eventTarget.offsetHeight;
                            let number = event.pageY - eventTarget.offsetTop;

                            if (currentItem.id !== event.target.id) {
                                currentItem.classList.remove('line-top');
                                currentItem.classList.remove('line-bottom');
                                console.log(currentItem.id, eventTarget.id);
                            }
                            currentItem = eventTarget;
                            // nodeItem.classList.add('alert-danger');
                            if (height / 2 > number) {
                                moveTo = 'before';
                                currentItem.classList.add('line-top');
                                currentItem.classList.remove('line-bottom');
                            } else {
                                moveTo = 'after';
                                currentItem.classList.add('line-bottom');
                                currentItem.classList.remove('line-top');
                            }
                            console.log(height, number)
                        } else {
                            console.log('doesnt match', eventTarget);
                        }
                    }
                    nodeItem.ondragend = async () => {
                        currentItem.classList.remove('line-top');
                        fromItem.classList.remove('line-top');
                        currentItem.classList.remove('line-bottom');
                        fromItem.classList.remove('line-bottom');
                        let object = {
                            from_id: parseInt(fromItem.id.replace(/[^\d.]/g, '')),
                            to_id: parseInt(currentItem.id.replace(/[^\d.]/g, '')),
                            move_to: moveTo
                        };
                        await this.moveElement(object);
                        this.loadNodes();
                    };
                    nodeItem.ondragenter = function(event) {
                    };
                }
            }
        }
    },
    async mounted() {
        await ApiService.get('/category-tree/list').then((data) => {
            this.task_categories = data.data;
        });
        this.loadNodes();

        this.emitter.on("toggle-sidebar", () => {
            this.loadNodes();
        });
    },
};
</script>
<style>
.line-top{
    border-top: 1px solid red;
    padding-top: 20px;
}
.line-bottom{
    border-bottom: 1px solid red;
    padding-bottom: 20px;
}
</style>
