<template>
  <q-card id="scroll-target-id" class="cardsList-container column">
    <q-infinite-scroll
      scroll-target="#scroll-target-id"
      :offset="250"
      @load="onLoad"
    >
      <div class="row cardsList-grid">
        <ygo-card
          v-for="card in cards"
          :key="card.id"
          :card="card"
          :clickable="true"
          :cardSelected="cardSelected"
          class="col-3 col-md-2 cardSelected"
          @click="emits('click:card', { card, quantity: 1 })"
          @hover:card="setCardStore"
        />
      </div>
      <template v-slot:loading>
        <div class="row justify-center q-my-md">
          <q-spinner-dots color="amber-6" size="40px" />
        </div>
      </template>
    </q-infinite-scroll>
  </q-card>
</template>

<script setup>
import { onMounted, reactive, ref, watch, defineEmits, defineProps } from "vue";
import { useQuasar, Platform } from "quasar";
import { useCardStore } from "src/stores/card";
import draggable from "vuedraggable";

import YgoCard from "src/components/cards/YgoCard.vue";

const $q = useQuasar();
const cardSelected = ref(null);

const store = useCardStore();
const setCardStore = (card) => {
  store.$patch({ card: card });
};

const props = defineProps({
  cards: {
    type: Array,
    required: true,
  },
});

const emits = defineEmits([
  "click:card",
  "hover:card",
  "click:add",
  "load:scroll",
]);

const onLoad = (index, done) => {
  console.log("onLoad", index);
  emits("load:scroll", index, done);
};
</script>

<style scoped lang="scss">
.cardsList-container {
  height: 90vh;
  overflow: auto;
  @media (max-width: $breakpoint-md) {
    height: 90vh;
    width: 100%;
  }
}

.cardsList-grid {
  @media (max-width: $breakpoint-md) {
    justify-content: space-around;
  }
  justify-content: center;
  gap: 0.3rem;
}
.cardSelected {
  border: solid 3px;
}
.cardSelected:hover {
  border-color: blueviolet;
}
</style>
