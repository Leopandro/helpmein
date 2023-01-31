import {defineStore} from "pinia";

export const usePermissionStore = defineStore("permissions", () => {
    function getUrlByRole(role: any)
    {
        if (role) {
            return {
                'Teacher': '/user/list',
                'Client': '/task/list',
                'Guest':'/greetings',
                '':'/greetings',
            }[role];
        } else {
            return '/greetings';
        }
    }
    return {
        getUrlByRole
    };
});
