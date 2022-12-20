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
                        if (event.target.matches('.accordion-item')) {
                            let height = event.target.offsetHeight;
                            let number = event.pageY - event.target.offsetTop;

                            if (currentItem.id !== event.target.id) {
                                currentItem.classList.remove('line-top');
                                currentItem.classList.remove('line-bottom');
                                console.log(currentItem.id, event.target.id);
                            }
                            currentItem = event.target;
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
                        }
                    }
                    nodeItem.ondragend = async () => {
                        let object = {
                            from_id: parseInt(fromItem.id.replace(/[^\d.]/g, '')),
                            to_id: parseInt(currentItem.id.replace(/[^\d.]/g, '')),
                            move_to: moveTo
                        };
                        await this.moveElement(object);
                        currentItem.classList.remove('line-top');
                        currentItem.classList.remove('line-bottom');
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
    },
};
</script>
<style>
.line-top{
    border-top: 1px solid red;
}
.line-bottom{
    border-bottom: 1px solid red;
}
</style>
