<template>
  <router-view />
</template>

<script>
import { defineComponent } from "vue";
import { useQuasar } from "quasar";

export default defineComponent({
  name: "App",
  setup() {
    const $q = useQuasar();
    const darkMode = localStorage.getItem("darkMode");
    $q.dark.set(darkMode === "true" ? true : false);
  },
});
</script>
