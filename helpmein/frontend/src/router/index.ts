import {
  createRouter,
  createWebHashHistory,
  type RouteRecordRaw,
} from "vue-router";
import { useAuthStore } from "@/stores/auth";
import { useConfigStore } from "@/stores/config";

const routes: Array<RouteRecordRaw> = [
  {
    path: "/",
    redirect: "/dashboard",
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
        path: "/task/list",
        name: "task-list",
        component: () => import("@/views/task/TaskListing.vue"),
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
          breadcrumbs: ["Apps", "User"],
        },
      },
      {
        path: "/user/create",
        name: "user-create",
        component: () => import("@/views/user/UserCreate.vue"),
        meta: {
          pageTitle: "Создать клиента",
          breadcrumbs: ["Apps", "User"],
        },
      },
      {
        path: "/user/edit/:id",
        name: "user-edit",
        component: () => import("@/views/user/UserEdit.vue"),
        meta: {
          pageTitle: "Редактировать клиента",
          breadcrumbs: ["Apps", "User"],
        },
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
          pageTitle: "Sign In",
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
          pageTitle: "Password update",
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
      next({ name: "sign-in" });
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
