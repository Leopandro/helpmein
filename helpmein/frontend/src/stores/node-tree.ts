import {ref} from "vue";
import {defineStore} from "pinia";


export const useNodeStore = defineStore("node", () => {
    const selectedNode = ref("");
    const selectedUser = ref("");
    function setNode(id) {
        selectedNode.value = id;
    }
    return {
        selectedNode,
        selectedUser,
        setNode
    };
});
