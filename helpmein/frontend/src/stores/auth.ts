import {ref} from "vue";
import {defineStore} from "pinia";
import ApiService from "@/core/services/ApiService";
import JwtService from "@/core/services/JwtService";

export interface User {
    name: string;
    surname: string;
    email: string;
    password: string;
    token: string;
}

export const useAuthStore = defineStore("auth", () => {
    const errors = ref("");
    const messages = ref("");
    const status = ref(500);
    const user = ref<User>({} as User);
    const permissions = ref("");
    const roles = ref("");
    const isAuthenticated = ref(!!JwtService.getToken());

    function setAuth(authUser: User) {
        isAuthenticated.value = true;
        user.value = authUser;
        errors.value = "";
        JwtService.saveToken(user.value.token);
    }

    function setError(error: any) {
        error ? errors.value = error : errors.value = '';
    }
    function setRoles(role: any) {
        role ? roles.value = role : roles.value = '';
    }

    function setStatus(statusCode: any) {
        status.value = statusCode;
    }

    function setPermissions(permissionData: any) {
        const permissionList: any = [];
        console.log(permissionData)
        if (!permissionData)
            return;
        permissionData.forEach((item: { name: string}) => {
            permissionList.push(item.name);
        });
        permissions.value = permissionList;
    }

    function setMessage(message: any) {
        messages.value = message;
    }

    function purgeAuth() {
        isAuthenticated.value = false;
        user.value = {} as User;
        errors.value = "";
        JwtService.destroyToken();
    }

    function login(credentials: User) {
        return ApiService.post("auth/login", credentials)
            .then(({data}) => {
                setAuth(data);
                setRoles(data.roles);
                setPermissions(data.permissions);
            })
            .catch(({response}) => {
                setError(response.data.message);
                setStatus(response.status);
            });
    }

    function logout() {
        purgeAuth();
    }

    function register(credentials: User) {
        return ApiService.post("auth/register", credentials)
            .then(({data}) => {
                setAuth(data.data);
            })
            .catch(({response}) => {
                setError(response.data.errors);
                setMessage(response.data.message);
                setStatus(response.status);
            });
    }

    function updatePassword(object) {
        return ApiService.post("auth/update-password", object)
            .then(() => {

            })
            .catch(({response}) => {
                setMessage(response.data.message);
                setError(response.data.errors);
                setStatus(response.status);
            });
    }

    function forgotPassword(email: string) {
        return ApiService.post("auth/remind-password", email)
            .then(() => {

            })
            .catch(({response}) => {
                setError(response.data.errors);
                setStatus(response.status);
            });
    }

    function verifyAuth() {
        if (JwtService.getToken()) {
            ApiService.setHeader();
            ApiService.post("verify_token")
                .then(({data}) => {
                    setAuth(data.data);
                })
                .catch(({response}) => {
                    setError(response.data.errors);
                    setStatus(response.status);
                    purgeAuth();
                });
        } else {
            purgeAuth();
        }
    }

    return {
        errors,
        messages,
        roles,
        permissions,
        user,
        isAuthenticated,
        login,
        logout,
        register,
        updatePassword,
        forgotPassword,
        verifyAuth,
    };
});
