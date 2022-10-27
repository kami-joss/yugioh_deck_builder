<template>
  <div>
    <modal-spinner v-model="waitingApi" />

    <h1>Decks</h1>
    <div class="deck-grid">
      <deck-cover v-for="deck in decks" :deck="deck" :key="deck.id" />
    </div>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import { api } from "boot/axios";
import { useRoute, useRouter } from "vue-router";
import { useQuasar, Platform } from "quasar";

import DeckCover from "src/components/decks/DeckCover.vue";
import ModalSpinner from "src/components/modals/ModalSpinner.vue";

const waitingApi = ref(false);

const decks = ref([]);
const getDecks = async () => {
  waitingApi.value = true;
  await api.get("/decks").then((res) => {
    decks.value = res.data.data;
    waitingApi.value = false;
  });
};

onMounted(async () => {
  await getDecks();
});
</script>

<style scoped>
.deck-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  flex-wrap: wrap;
  gap: 1rem;
}
</style>
