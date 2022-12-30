import {ref} from "vue";
import {defineStore} from "pinia";


export const useNodeStore = defineStore("node", () => {
    const selectedNode = ref("");
    function setNode(node) {
        selectedNode.value = Object.assign({}, node);
    }
    return {
        selectedNode,
    };
});
