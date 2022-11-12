<template>
  <div>
    <q-avatar size="xl" text-color="white" color="primary">
      <img :src="userStore.user?.image_path" />
      <q-menu transition-show="flip-right" transition-hide="flip-left">
        <q-list style="min-width: 100px">
          <q-item clickable tag="a" :href="`#/user/${userStore.getUser.id}`">
            <q-item-section>Manage</q-item-section>
          </q-item>
          <q-item clickable @click="userStore.logout()">
            <q-item-section>Loggout</q-item-section>
          </q-item>
        </q-list>
      </q-menu>
    </q-avatar>
  </div>
</template>

<script setup>
import { useUserStore } from "src/stores/user";
const userStore = useUserStore();
</script>
