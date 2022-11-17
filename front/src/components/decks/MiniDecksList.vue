<template>
  <q-card class="deck-list-container">
    <q-toolbar class="bg-primary text-white shadow-2">
      <q-toolbar-title>Decks playing this card</q-toolbar-title>
      <q-btn icon="close" flat round dense v-close-popup />
    </q-toolbar>
    <q-card-section>
      <q-list separator bordered class="list">
        <q-item
          v-for="deck in decks"
          :key="deck.id"
          clickable
          @click="router.push(`/decks/${deck.id}`)"
        >
          <q-item-section>
            <q-item-label>{{ deck.name }}</q-item-label>
            <q-item-label caption>{{ deck.user?.name }}</q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
    </q-card-section>
  </q-card>
</template>

<script setup>
import { defineProps } from "vue";
import { useUserStore } from "src/stores/user";
import { useRouter } from "vue-router";

const router = useRouter();
const userStore = useUserStore();
const props = defineProps({
  decks: {
    type: Array,
    required: true,
  },
});
</script>

<style scoped lang="scss">
.deck-list-container {
  width: 100%;
  max-width: 500px;
  margin: 0 auto;
}

.list {
  max-height: 500px;
  overflow-y: auto;
}
</style>
