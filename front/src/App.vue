<template>
  <router-view />
</template>

<script>
import { defineComponent, watch } from "vue";
import { useQuasar } from "quasar";
import { useUserStore } from "src/stores/user";
import { useTabStore } from "src/stores/tab";
import { api } from "./boot/axios";
import { useRoute } from "vue-router";

export default defineComponent({
  name: "App",
  setup() {
    const $q = useQuasar();
    const darkMode = localStorage.getItem("darkMode");
    $q.dark.set(darkMode === "true" ? true : false);
  },
  created() {
    const userStore = useUserStore();
    const tabStore = useTabStore();
    const userData = localStorage.getItem("user");
    const userToken = localStorage.getItem("token");
    const route = useRoute();

    if (userData && userToken) {
      userStore.$patch({
        user: JSON.parse(userData),
        token: userToken,
      });
      api.defaults.headers.common["Authorization"] = `Bearer ${userToken}`;
    }

    tabStore.$patch({
      tab: localStorage.getItem("tab"),
    });
    watch(
      () => route.name,
      (val) => {
        const tabDeckBuilder = ["deck-create", "deck-edit"];
        const tabDecks = ["decks", "deck-view"];
        const tabHome = ["home"];

        if (tabDeckBuilder.includes(val)) {
          tabStore.setTab("deck-builder");
          localStorage.setItem("tab", "deck-builder");
        } else if (tabDecks.includes(val)) {
          tabStore.setTab("decks");
          localStorage.setItem("tab", "decks");
        } else if (tabHome.includes(val)) {
          tabStore.setTab("home");
          localStorage.setItem("tab", "home");
        } else {
          tabStore.setTab("");
          localStorage.setItem("tab", "");
        }
      }
    );
  },
});
</script>
