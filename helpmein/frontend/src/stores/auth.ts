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
    const avatar = ref("/media/avatars/blank.png");
    const status = ref(500);
    const user = ref<User>({} as User);
    const permissions = ref("");
    const roles = ref("");
    const currentRole = ref("Guest");
    try {
        currentRole.value = JSON.parse(localStorage.getItem('currentRole') ?? "");
    } catch(e) {
        currentRole.value = "Guest";
    }
    try {
        roles.value = JSON.parse(localStorage.getItem('roles') ?? "");
    } catch(e) {
        roles.value = "";
    }
    const isAuthenticated = ref(!!JwtService.getToken());

    function setCurrentRole(value: any) {
        if (value) {
            localStorage.setItem('currentRole', JSON.stringify(value));
            currentRole.value = value;
        } else {
            currentRole.value = 'Guest';
        }
    }
    function setAuth(authUser: User) {
        isAuthenticated.value = true;
        user.value = authUser;
        errors.value = "";
        JwtService.saveToken(authUser.token);
    }
    function setUserData(authUser: User) {
        user.value = authUser;
        console.log(authUser);
        avatar.value = authUser.avatar?.url ?? "/media/avatars/blank.png";
        errors.value = "";
    }

    function setError(error: any) {
        error ? errors.value = error : errors.value = '';
    }
    function setRoles(role: any) {
        if (role) {
            localStorage.setItem('roles', JSON.stringify(role));
            roles.value = role;
        } else {
            roles.value = '';
        }
    }

    function setStatus(statusCode: any) {
        status.value = statusCode;
    }

    function setPermissions(permissionData: any) {
        const permissionList: any = [];
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
                verifyAuth();
                setRoles(data.roles);
                setCurrentRole(data.roles[0]);
                setMessage('');
                setPermissions(data.permissions);
            })
            .catch(({response}) => {
                setError(response.data.errors);
                setMessage(response.data.message);
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
                verifyAuth();
                setRoles(data.data.roles);
                setMessage('');
                setPermissions(data.data.permissions);
            })
            .catch(({response}) => {
                setError(response.data.errors);
                setMessage(response.data.message);
                setStatus(response.status);
            });
    }

    function updatePassword(object) {
        return ApiService.post("auth/update-password", object)
            .then((response) => {
                setMessage('');
                setError('');
                setStatus(response.status);
            })
            .catch(({response}) => {
                setMessage(response.data.message);
                setError(response.data.errors);
                setStatus(response.status);
            });
    }

    function forgotPassword(email: string) {
        return ApiService.post("auth/remind-password", email)
            .then((response) => {
                console.log(response);
                setMessage(response.data.data.message);
                setError('');
                setStatus(response.status);
            })
            .catch(({response}) => {
                setMessage(response.data.message);
                setError(response.data.errors);
                setStatus(response.status);
            });
    }

    function verifyAuth() {
        if (JwtService.getToken()) {
            ApiService.setHeader();
            ApiService.post("verify_token")
                .then(({data}) => {
                    setUserData(data.data);
                })
                .catch(({response}) => {
                    console.log(response)
                    setError(response.data ? response.data.errors : '');
                    setStatus(response.status);
                    window.location.href = '/#/sign-in';
                    window.location.reload();
                    // router.push('/sign-in').then();
                    purgeAuth();
                });
        } else {
            purgeAuth();
        }
    }

    return {
        avatar,
        errors,
        messages,
        roles,
        permissions,
        user,
        isAuthenticated,
        currentRole,
        setCurrentRole,
        login,
        logout,
        register,
        updatePassword,
        forgotPassword,
        verifyAuth,
    };
});
