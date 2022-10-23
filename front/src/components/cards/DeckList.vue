<template>
  <q-card class="deckList-container">
    <div class="row deckList-grid">
      <ygo-card
        v-for="card in deck"
        :key="card.id"
        :card="card"
        :clickable="true"
        @click="removeCardFromDeck"
        class="col-1"
      />
    </div>
  </q-card>
</template>

<script setup>
import { onMounted, reactive, ref, watch, defineProps, defineEmits } from "vue";

import YgoCard from "src/components/cards/YgoCard.vue";

const props = defineProps({
  deck: {
    type: Array,
    required: true,
  },
});

const removeCardFromDeck = (card) => {
  emits("remove", card);
};
const addCardToDeck = (card) => {
  emits("add", card);
};

const emits = defineEmits(["remove", "add"]);
</script>

<style scoped lang="scss">
.deckList-container {
  overflow: auto;
}

.deckList-grid {
  gap: 0.3rem;
}
</style>
