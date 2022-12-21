import {ref} from "vue";
import {defineStore} from "pinia";


export const useNodeStore = defineStore("node", () => {
    const nodes = ref("");

    function setStatus(nodeId: string, status: boolean) {
        nodes[nodeId] = {};
        nodes[nodeId].status = status;
        console.log(nodes);
    }

    function getStatus(nodeId: string) {
        if (nodes[nodeId]) {
            return nodes[nodeId].status
        }
        return true;
    }


    return {
        nodes,
        getStatus,
        setStatus
    };
});
