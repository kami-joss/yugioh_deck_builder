<template>
  <q-card
    id="scroll-target-id"
    class="cardsList-container column"
    @mouseleave="onMouseLeave"
  >
    <q-infinite-scroll
      scroll-target="#scroll-target-id"
      :offset="250"
      @load="onLoad"
    >
      <div class="row cardsList-grid">
        <div
          v-for="card in cards"
          :key="card.id"
          class="col-4 col-sm-3 cardSelected"
        >
          <p v-if="!cards.length" class="text-center">No cards found</p>
          <ygo-card
            :card="card"
            clickable
            :withAdd="
              (route.name == 'deck-create' || route.name == 'deck-edit') &&
              $q.platform.is.mobile
                ? true
                : false
            "
            @click="emits('click:card', { card, quantity: 1 })"
            @click:add:multiple="emits('click:card', $event)"
            @hover:card="onHoverCard"
          />
        </div>
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
import { debounce } from "lodash";
import { useRoute } from "vue-router";

import YgoCard from "src/components/cards/YgoCard.vue";

const $q = useQuasar();

const store = useCardStore();
const route = useRoute();

console.log(route.name);

// const onMultiple = (payload) => {
//   console.log(payload);
//   emits("click:add", payload);
// };

const onHoverCard = (card) => {
  setCardStore(card);
};

const setCardStore = debounce(
  (card) => {
    store.$patch({ card: card });
  },
  $q.platform.is.mobile ? 0 : 500
);

const onMouseLeave = () => {
  setCardStore.cancel();
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
  "add:multiple",
]);

const onLoad = (index, done) => {
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
  // justify-content: center;
  // gap: 0.3rem;
}
.cardSelected {
  border: solid 3px;
}
.cardSelected:hover {
  border-color: $warning;
  transition-duration: 150ms;
}
</style>
