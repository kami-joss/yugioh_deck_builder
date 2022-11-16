<template>
  <div class="q-pa-md">
    <q-layout view="hHh Lpr lff">
      <q-header
        v-if="$q.platform.is.mobile"
        class="bg-primary text-white"
        elevated
      >
        <q-toolbar>
          <q-toolbar-title
            ><div class="title">
              Pro Deck Builder Yu-gi-oh!
            </div></q-toolbar-title
          >
          <q-btn
            flat
            dense
            round
            icon="menu"
            aria-label="Menu"
            @click="toggleLeftDrawer"
          />
        </q-toolbar>
      </q-header>

      <q-drawer v-model="leftDrawerOpen" overlay bordered>
        <q-list>
          <q-item>
            <q-item-section>
              <q-btn
                size="md"
                label="close"
                icon="close"
                @click="toggleLeftDrawer"
              />
            </q-item-section>
          </q-item>
          <q-item>
            <q-item-section>
              <q-toggle
                v-model="darkMode"
                checked-icon="dark_mode"
                unchecked-icon="light_mode"
                size="lg"
              />
            </q-item-section>
          </q-item>
          <q-item
            v-if="userStore.getUser"
            clickable
            @click="router.push(`/user/${userStore.user.id}`)"
            class="text-bold text-white bg-primary"
          >
            <q-item-section side>
              <q-avatar>
                <img :src="userStore.user?.image_path" />
              </q-avatar>
            </q-item-section>
            <q-item-section>
              <q-item-label>{{ userStore.user?.name }}</q-item-label>
            </q-item-section>
            <q-item-section>
              <q-btn flat dense round icon="settings" />
            </q-item-section>
          </q-item>
          <EssentialLink
            v-else
            :link="'#/login'"
            :title="'Login'"
            :icon="'login'"
          />

          <EssentialLink
            v-for="link in linksList"
            :link="link.link"
            :title="link.title"
            :icon="link.icon"
            :key="link.title"
            v-bind="link"
          />

          <q-item v-if="userStore.getToken">
            <q-item-section side>
              <q-btn
                flat
                dense
                round
                icon="logout"
                @click="userStore.logout()"
              />
            </q-item-section>
            <q-item-section>
              <q-item-label> Logout </q-item-label>
            </q-item-section>
          </q-item>
        </q-list>
      </q-drawer>

      <q-header
        v-if="!$q.platform.is.mobile"
        class="row justify-between items-center q-px-md header"
        elevated
      >
        <div class="title">Pro Deck Builder Yu-gi-oh!</div>

        <div class="navbar">
          <main-navbar />
        </div>

        <div class="row items-center gap-2">
          <q-toggle
            v-model="darkMode"
            checked-icon="dark_mode"
            unchecked-icon="light_mode"
            color="dark"
            size="lg"
          />
          <div v-if="userStore.getUser" class="row items-center gap-2">
            <q-btn
              flat
              dense
              icon="favorite"
              aria-label="Davorites"
              label="My favorites"
              :stack="true"
              @click="router.push(`/user/${userStore.getUser.id}/favorites`)"
            />
            <q-btn
              flat
              dense
              icon="style"
              aria-label="My decks"
              label="My Decks"
              :stack="true"
              @click="router.push(`/user/${userStore.getUser.id}/decks`)"
            />
            <UsersHeaderMenu />
          </div>
          <q-btn v-else flat @click="router.push('/login')">
            <span>Se connecter</span>
          </q-btn>
        </div>
      </q-header>

      <q-page-container>
        <q-page>
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
import { useUserStore } from "src/stores/user";
import UsersHeaderMenu from "src/components/users/UsersHeaderMenu.vue";
import { useRouter } from "vue-router";

import MainNavbar from "src/components/layout/MainNavbar.vue";

const linksList = [
  {
    title: "Home",
    link: "/#/",
    icon: "home",
  },
  {
    title: "Decks",
    link: "/#/decks",
    icon: "style",
  },
  {
    title: "Deck Builder",
    link: "/#/decks/create",
    icon: "construction",
  },
];

export default defineComponent({
  name: "MainLayout",

  components: {
    UsersHeaderMenu,
    MainNavbar,
    EssentialLink,
  },

  setup() {
    const leftDrawerOpen = ref(false);

    const $q = useQuasar();
    const router = useRouter();

    const darkMode = ref(
      localStorage.getItem("darkMode") == "true" ? true : false
    );

    watch(darkMode, (val) => {
      $q.dark.toggle();
      localStorage.setItem("darkMode", val);
    });

    const userStore = useUserStore();

    return {
      leftDrawerOpen,
      toggleLeftDrawer() {
        leftDrawerOpen.value = !leftDrawerOpen.value;
      },
      darkMode,
      userStore,
      router,
      MainNavbar,
      $q,
      linksList,
    };
  },
});
</script>

<style scoped lang="scss">
.navbar {
  @media screen and (min-width: 1200px) {
    display: none;
    width: 100%;
    position: absolute;
    display: flex;
    justify-content: center;
    background-color: rgb(0, 0, 0, 0);
  }
}
.title {
  font-size: 1.2rem;
  font-weight: bold;
  color: #fff;
  padding: 0.5rem;
}
</style>
