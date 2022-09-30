<template>
  <div class="q-pa-md">
    <q-layout view="hHh Lpr lff">
      <q-header elevated>
        <q-toolbar>
          <q-btn
            flat
            dense
            round
            icon="menu"
            aria-label="Menu"
            @click="toggleLeftDrawer"
          />

          <q-toolbar-title>
            Pro Deck Builder <br />
            Yu-gi-oh!
          </q-toolbar-title>

          <q-input rounded outlined :bg-color="'white'">
            <template v-slot:append>
              <q-btn flat rounded icon="search" />
            </template>
          </q-input>

          <q-toggle
            v-model="darkMode"
            checked-icon="dark_mode"
            unchecked-icon="light_mode"
            color="dark"
            size="lg"
          />
          <div>
            <a href="#/login"> Se connecter </a>
          </div>
        </q-toolbar>
      </q-header>

      <q-drawer v-model="leftDrawerOpen" overlay bordered>
        <q-list>
          <q-item-label header> Essential Links </q-item-label>

          <EssentialLink
            v-for="link in essentialLinks"
            :key="link.title"
            v-bind="link"
          />
        </q-list>
      </q-drawer>

      <q-page-container>
        <q-page padding>
          <router-view />
        </q-page>
      </q-page-container>
    </q-layout>
  </div>
</template>

<script>
import { defineComponent, ref, watch } from "vue";
import EssentialLink from "components/EssentialLink.vue";
import { useQuasar } from "quasar";

const linksList = [
  {
    title: "Home",
    link: "/#/",
  },
  {
    title: "Decks",
    link: "/#/decks",
  },
  {
    title: "Deck Builder",
    link: "/#/deck-builder",
  },
];

export default defineComponent({
  name: "MainLayout",

  components: {
    EssentialLink,
  },

  setup() {
    const leftDrawerOpen = ref(false);
    const $q = useQuasar();
    const darkMode = ref(
      localStorage.getItem("darkMode") == "true" ? true : false
    );

    watch(darkMode, (val) => {
      $q.dark.toggle();
      localStorage.setItem("darkMode", val);
    });

    return {
      essentialLinks: linksList,
      leftDrawerOpen,
      toggleLeftDrawer() {
        leftDrawerOpen.value = !leftDrawerOpen.value;
      },
      darkMode,
    };
  },
});
</script>
