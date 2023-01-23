import {defineStore} from "pinia";
import {ref} from "vue";











export const useRouterStore = defineStore("router", () => {
    let currentTitle = ref("");
    return {
        currentTitle
    };
});
