import {
    createRouter,
    createWebHashHistory,
    type RouteRecordRaw,
} from "vue-router";
import {useAuthStore} from "@/stores/auth";
import {useConfigStore} from "@/stores/config";
import {usePermissionStore} from "@/stores/permission";

const routes: Array<RouteRecordRaw> = [
    {
        path: "/",
        redirect: to => {
            // the function receives the target route as the argument
            // a relative location doesn't start with `/`
            // or { path: 'profile'}
            const auth = useAuthStore();
            const permission = usePermissionStore();
            return permission.getUrlByRole(auth.roles);
        },
        component: () => import("@/layouts/main-layout/MainLayout.vue"),
        meta: {
            middleware: "auth",
        },
        children: [
            {
                path: "/dashboard",
                name: "dashboard",
                component: () => import("@/views/Dashboard.vue"),
                meta: {
                    pageTitle: "Dashboard",
                    breadcrumbs: ["Dashboards"],
                },
            },
            {
                path: "/how-to-node",
                name: "how-to-node",
                component: () => import("@/views/faq/NodeEdit.vue"),
                meta: {
                    pageTitle: "Инструкция к дереву папок",
                    breadcrumbs: ["Инструкция к дереву папок"],
                },
            },
            {
                path: "/greetings",
                name: "greetings",
                component: () => import("@/views/guest/Greetings.vue"),
                meta: {
                    pageTitle: "Добро пожаловать",
                    breadcrumbs: ["Стартовая страница"],
                },
            },
            {
                path: "/task/list",
                name: "task-list",
                component: () => import("@/views/client-task/ClientTaskList.vue"),
                meta: {
                    pageTitle: "Список задач",
                    breadcrumbs: ["Задачи"],
                },
            },
            {
                path: "/task/solve/:id",
                name: "task-solve",
                component: () => import("@/views/client-task/ClientTaskSolve.vue"),
                meta: {
                    pageTitle: "Список задач",
                    breadcrumbs: ["Задачи"],
                },
            },
            {
                path: "/user/list",
                name: "user-list",
                component: () => import("@/views/user/UserListing.vue"),
                meta: {
                    pageTitle: "Список клиентов",
                    breadcrumbs: ["Клиенты"],
                },
            },
            {
                path: "/task-category/list",
                name: "task-category-list",
                component: () => import("@/views/task-category/TaskCategoryTree.vue"),
                meta: {
                    pageTitle: "Категории и задачи",
                    breadcrumbs: ["Категории и задачи"],
                },
            },
            {
                path: "/task-category/assignment",
                name: "task-category-assignment",
                component: () => import("@/views/task-category/TaskCategoryAssign.vue"),
                meta: {
                    pageTitle: "Назначение задач",
                    breadcrumbs: ["Назначение задач"],
                },
            },
            {
                path: "/task/edit/:id",
                name: "task-edit-id",
                component: () => import("@/views/task-category/TaskCreate.vue"),
                meta: {
                    pageTitle: "Редактирование задачи",
                    breadcrumbs: [{
                        "name": "Задачи",
                        "path": "/task/list"
                    }, {
                        "name": "Редактировать задачу",
                        "path": "task/create"
                    }],
                },
            },
            {
                path: "/task/edit",
                name: "task-edit",
                component: () => import("@/views/task-category/TaskCreate.vue"),
                meta: {
                    pageTitle: "Создание задачи",
                    breadcrumbs: [{
                        "name": "Задачи",
                        "path": "/task/list"
                    }, {
                        "name": "Создать задачу",
                        "path": "task/create"
                    }],
                },
            },
            {
                path: "/admin/task/list",
                name: "admin-task-list",
                component: () => import("@/views/task-category/TaskList.vue"),
                meta: {
                    pageTitle: "Список задач",
                    breadcrumbs: [
                        {
                            "name": "Задачи",
                            "path": "task/list"
                        }
                        ],
                },
            },
            {
                path: "/user/create",
                name: "user-create",
                component: () => import("@/views/user/UserCreate.vue"),
                meta: {
                    pageTitle: "Создать клиента",
                    breadcrumbs: ["Клиенты"],
                },
            },
            {
                path: "/profile",
                name: "profile",
                component: () => import("@/views/profile/ProfileEdit.vue"),
                meta: {
                    pageTitle: "Редактировать профиль",
                },
            },
            {
                path: "/user/edit/:id/",
                name: "user",
                children: [
                    {
                        path: "",
                        component: () => import("@/views/user/UserEdit.vue"),
                        meta: {
                            pageTitle: "Редактировать клиента",
                            breadcrumbs: ["Редактирование клиента"],
                        },
                    },
                    {
                        name: "user-task-list",
                        path: "task",
                        component: () => import("@/views/user/UserTaskList.vue"),
                    }
                ],
            },
        ],
    },
    {
        path: "/",
        component: () => import("@/layouts/AuthLayout.vue"),
        children: [
            {
                path: "/sign-in",
                name: "sign-in",
                component: () =>
                    import("@/views/crafted/authentication/basic-flow/SignIn.vue"),
                meta: {
                    pageTitle: "Войти",
                },
            },
            {
                path: "/sign-up",
                name: "sign-up",
                component: () =>
                    import("@/views/crafted/authentication/basic-flow/SignUp.vue"),
                meta: {
                    pageTitle: "Sign Up",
                },
            },
            {
                path: "/password-reset",
                name: "password-reset",
                component: () =>
                    import("@/views/crafted/authentication/basic-flow/PasswordReset.vue"),
                meta: {
                    pageTitle: "Password reset",
                },
            },
            {
                path: "/password-update",
                name: "password-update",
                component: () =>
                    import("@/views/crafted/authentication/basic-flow/PasswordUpdate.vue"),
                meta: {
                    pageTitle: "Восстановление пароля",
                },
            },
        ],
    },
    {
        path: "/:pathMatch(.*)*",
        redirect: "/404",
    },
];

const router = createRouter({
    history: createWebHashHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();
    const configStore = useConfigStore();

    // current page view title
    document.title = `${to.meta.pageTitle} - ${import.meta.env.VITE_APP_NAME}`;

    // reset config to initial state
    configStore.resetLayoutConfig();

    // verify auth token before each page change
    authStore.verifyAuth();

    // before page access check if page requires authentication
    if (to.meta.middleware == "auth") {
        if (authStore.isAuthenticated) {
            next();
        } else {
            next({name: "sign-in"});
        }
    } else {
        next();
    }

    // Scroll page to top on every route change
    window.scrollTo({
        top: 0,
        left: 0,
        behavior: "smooth",
    });
});

export default router;
