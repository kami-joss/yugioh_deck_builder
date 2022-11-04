<template>
  <q-card class="deckList-container">
    <div class="row deckList-grid">
      <ygo-card
        v-for="card in deck"
        :key="card.id"
        :card="card"
        :clickable="true"
        class="col-1"
        @click="removeCardFromDeck"
        @hover:card="setCardStore"
      />
    </div>
  </q-card>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";
import { useCardStore } from "src/stores/card";
import YgoCard from "src/components/cards/YgoCard.vue";

const store = useCardStore();
const setCardStore = (card) => {
  store.$patch({ card: card });
};

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
