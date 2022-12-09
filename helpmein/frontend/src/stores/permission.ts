import {defineStore} from "pinia";

export const usePermissionStore = defineStore("permissions", () => {
    function getUrlByRole(role: any)
    {
        return {
            'Teacher': '/user/list',
            'Client': '/task/list',
        }[role[0]];
    }
    return {
        getUrlByRole
    };
});
