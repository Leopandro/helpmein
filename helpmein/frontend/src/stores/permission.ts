import {defineStore} from "pinia";

export const usePermissionStore = defineStore("permissions", () => {
    function getUrlByRole(role: Array<any>)
    {

        return {
            'Teacher': 'user-list',
            'Client': 'task-list',
        }[role[0]];
    }
    return {
        getUrlByRole
    };
});
