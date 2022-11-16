<template>
  <q-card class="deckList-container">
    <div class="row deckList-grid">
      <div
        v-for="(card, index) in deck"
        :key="index"
        :class="{
          'col-1': $q.platform.is.desktop,
          'col-2': $q.platform.is.mobile,
        }"
      >
        <ygo-card
          :card="card"
          :clickable="true"
          :with-less-btn="
            (route.name == 'deck-create' || route.name == 'deck-edit') &&
            $q.platform.is.mobile
              ? true
              : false
          "
          @click="emits('remove', { card, quantity: 1 })"
          @hover:card="setCardStore"
        />
      </div>
    </div>
  </q-card>
</template>

<script setup>
import { defineProps, defineEmits, ref } from "vue";
import { useCardStore } from "src/stores/card";
import { useRoute } from "vue-router";

import YgoCard from "src/components/cards/YgoCard.vue";

const route = useRoute();

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
