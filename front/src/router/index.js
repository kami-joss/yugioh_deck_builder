import { route } from "quasar/wrappers";
import { useUserStore } from "src/stores/user";
import {
  createRouter,
  createMemoryHistory,
  createWebHistory,
  createWebHashHistory,
} from "vue-router";
import routes from "./routes";
import { api } from "boot/axios";

/*
 * If not building with SSR mode, you can
 * directly export the Router instantiation;
 *
 * The function below can be async too; either use
 * async/await or return a Promise which resolves
 * with the Router instance.
 */

export default route(function (/* { store, ssrContext } */) {
  const createHistory = process.env.SERVER
    ? createMemoryHistory
    : process.env.VUE_ROUTER_MODE === "history"
    ? createWebHistory
    : createWebHashHistory;

  const Router = createRouter({
    scrollBehavior: () => ({ left: 0, top: 0 }),
    routes,

    // Leave this as is and make changes in quasar.conf.js instead!
    // quasar.conf.js -> build -> vueRouterMode
    // quasar.conf.js -> build -> publicPath
    history: createHistory(
      process.env.MODE === "ssr" ? void 0 : process.env.VUE_ROUTER_BASE
    ),
  });

  Router.beforeEach(async (to, from) => {
    const userStore = useUserStore();
    const LoggedIn = userStore.getUser;

    api.interceptors.response.use(
      function (config) {
        // console.log(error.response.status);
        // Do something before request is sent
        return config;
      },
      function (error) {
        // Do something with request error
        if (error.response.status === 403) {
          Router.replace("/redirect");
        } else {
          return Promise.reject(error);
        }
      }
    );

    const protectedRoutes = [
      "deck-create",
      "deck-edit",
      "user",
      "user-favorites",
      "user-decks",
    ];

    if (
      (LoggedIn && to.path === "/login") ||
      (LoggedIn && to.path === "/register")
    ) {
      return "/";
    }

    if (!LoggedIn && protectedRoutes.includes(to.name)) {
      return "/redirect";
    }
  });

  return Router;
});
