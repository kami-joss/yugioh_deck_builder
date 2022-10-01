<template>
  <q-card class="deckList-container">
    <div class="row deckList-grid">
      <ygo-card
        v-for="card in deck"
        :key="card.id"
        :card="card"
        :clickable="true"
        @click="removeCardFromDeck"
        class="col-xs-12 col-sm-6 col-md-4 col-lg-2 col"
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
  max-height: 90vh;
  overflow: auto;
}

.deckList-grid {
  gap: 1rem;
}
</style>
