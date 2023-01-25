<template>
    <div class="container-fluid row">
        <div class="col-6 p-0">
            <node-tree ref="node-tree" :item="this.task_categories" :value="this.task_categories.name"></node-tree>
        </div>
        <div class="col-6">
            <TaskList></TaskList>
        </div>
    </div>
</template>

<script>
import ApiService from "@/core/services/ApiService";
import NodeTree from "@/components/node-tree/NodeTree.vue";
import TaskList from "./TaskList.vue";

export default {
    name: "TaskCategoryTree",
    components: {
        NodeTree,
        TaskList
    },
    init() {
    },
    data() {
        return {
            folder_id: '',
            tableData: [],
            search: [],
            selectedIds: [],
            deleteFewCustomers: [],
            sort: [],
            task_categories: []
        }
    },
    methods: {
        async getItems() {
            await ApiService.get('/category-tree/list').then((data) => {
                this.task_categories = data.data;
            })
            this.loadNodes();
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
                        // fromItem.style.display = "none";
                        currentItem = event.target;
                    };
                    nodeItem.ondragover = (event) => {
                        let folderTarget = event.target.closest('.add-to-folder');
                        let eventTarget = event.target.closest('.accordion-item');
                        if (eventTarget) {

                            let height = eventTarget.offsetHeight;
                            let number = event.pageY - eventTarget.offsetTop;

                            if (currentItem.id !== event.target.id) {
                                currentItem.classList.remove('line-top');
                                currentItem.classList.remove('line-bottom');
                                currentItem.classList.remove('border-primary');
                            }
                            currentItem = eventTarget;
                            // nodeItem.classList.add('alert-danger');
                            if (number < 5) {
                                moveTo = 'before';
                                currentItem.classList.add('line-top');
                                currentItem.classList.remove('line-bottom');
                                currentItem.classList.remove('border-primary');
                            } else if (number > 25) {
                                moveTo = 'after';
                                currentItem.classList.add('line-bottom');
                                currentItem.classList.remove('line-top');
                                currentItem.classList.remove('border-primary');
                            } else {
                                moveTo = 'inner';
                                currentItem.classList.add('border-primary');
                                currentItem.classList.remove('line-top');
                                currentItem.classList.remove('line-bottom');
                            }
                            console.log(height, number)
                        } else {
                        }
                    }
                    nodeItem.ondragend = async () => {
                        fromItem.style.display = "block";
                        currentItem.classList.remove('line-top');
                        fromItem.classList.remove('line-top');
                        currentItem.classList.remove('line-bottom');
                        fromItem.classList.remove('line-bottom');
                        fromItem.classList.remove('border-primary');
                        currentItem.classList.remove('border-primary');
                        let object = {
                            from_id: parseInt(fromItem.id.replace(/[^\d.]/g, '')),
                            to_id: parseInt(currentItem.id.replace(/[^\d.]/g, '')),
                            move_to: moveTo
                        };
                        await this.moveElement(object);
                        await this.getItems();
                    };
                    nodeItem.ondragenter = function(event) {
                    };
                }
            }
        }
    },
    async mounted() {
        await this.getItems();

        this.emitter.on("reload-tree", () => {
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
