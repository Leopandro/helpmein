import {defineStore} from "pinia";

export const usePermissionStore = defineStore("permissions", () => {
    function getUrlByRole(role: any)
    {
        if (role) {
            return {
                'Teacher': '/user/list',
                'Client': '/task/list',
            }[role[0]];
        } else {
            return '/dashboard';
        }
    }
    return {
        getUrlByRole
    };
});
