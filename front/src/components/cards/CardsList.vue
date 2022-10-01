<template>
  <q-card class="cardsList-container column">
    <q-infinite-scroll @load="onLoad">
      <div class="row cardsList-grid">
        <ygo-card
          v-for="card in cards"
          :key="card.id"
          :card="card"
          :clickable="true"
          @click="emits('click', card)"
          @hover:card="emits('hover:card', card)"
          class="col-2"
        />
      </div>
      <template v-slot:loading>
        <div class="row justify-center q-my-md">
          <q-spinner-dots color="primary" size="40px" />
        </div>
      </template>
    </q-infinite-scroll>
  </q-card>
</template>

<script setup>
import { onMounted, reactive, ref, watch, defineEmits, defineProps } from "vue";

import YgoCard from "src/components/cards/YgoCard.vue";

const props = defineProps({
  cards: {
    type: Array,
    required: true,
  },
});

const onLoad = (index, done) => {
  emits("load:scroll", index, done);
};

const emits = defineEmits(["click", "load:scroll", "hover:card"]);
</script>

<style scoped lang="scss">
.cardsList-container {
  height: 80vh;
  overflow: auto;
}

.cardsList-grid {
  gap: 0.3rem;
}
</style>
