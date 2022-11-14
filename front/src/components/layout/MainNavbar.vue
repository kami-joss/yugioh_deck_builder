<template>
  <div class="row justify-between">
    <q-tabs v-model="tabActive" dense>
      <q-tab
        name="home"
        icon="home"
        label="home"
        class="btn-navabar"
        @click="router.push('/')"
      />
      <q-tab
        name="decks"
        icon="view_carousel"
        label="decks"
        class="btn-navabar"
        @click="router.push('/decks')"
      />
      <q-tab
        name="deck-builder"
        icon="construction"
        label="deck-builder"
        class="btn-navabar"
        @click="router.push('/decks/create')"
      />
    </q-tabs>
  </div>
</template>

<script setup>
import { useRouter } from "vue-router";
import { useTabStore } from "src/stores/tab";
import { ref, watch } from "vue";

const router = useRouter();
const tabStore = useTabStore();

const tabActive = ref(tabStore.getTab);

watch(
  () => tabActive.value,
  async () => {
    localStorage.setItem("tab", tabActive.value);
    tabStore.setTab(tabActive.value);
  }
);

tabStore.$subscribe((state) => {
  tabActive.value = state.events.newValue;
});
</script>

<style scoped>
.btn-navabar {
  padding-right: 50px;
  padding-left: 50px;
}
</style>
