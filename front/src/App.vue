<template>
  <router-view />
</template>

<script>
import { defineComponent } from "vue";
import { useQuasar } from "quasar";
import { useUserStore } from "src/stores/user";
import { api } from "./boot/axios";

export default defineComponent({
  name: "App",
  setup() {
    const $q = useQuasar();
    const darkMode = localStorage.getItem("darkMode");
    $q.dark.set(darkMode === "true" ? true : false);
  },
  created() {
    const userStore = useUserStore();
    const userData = localStorage.getItem("user");
    const userToken = localStorage.getItem("token");
    if (userData) {
      userStore.$patch({ user: JSON.parse(userData) });
      api.defaults.headers.common["Authorization"] = `Bearer ${userToken}`;
    }

    api.interceptors.response.use(
      (response) => response,
      (error) => {
        if (error.response.status === 401) {
          userStore.logout();
        }
        return Promise.reject(error);
      }
    );
  },
});
</script>
