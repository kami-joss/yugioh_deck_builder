<template>
  <q-card class="cardsList-container" ref="scrollTargetRef">
    <q-infinite-scroll
      @load="onLoad"
      :offset="250"
      :scroll-target="scrollTargetRef"
    >
      <div class="row justify-center cardsList-grid">
        <ygo-card
          v-for="card in cards"
          :key="card.id"
          :card="card"
          :clickable="true"
          @click="emits('click', card)"
          class="col-xs-12 col-sm-6 col-md-4 col-lg-2 col"
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

const scrollTargetRef = ref(null);

const props = defineProps({
  cards: {
    type: Array,
    required: true,
  },
});

const onLoad = (index, done) => {
  emits("load", index, done);
};

const emits = defineEmits(["click", "load"]);

const state = reactive({
  cards: {
    data: [],
    meta: {},
  },
  page: 1,
  search: "",
});
</script>

<style scoped lang="scss">
.cardsList-container {
  max-height: 90vh;
  overflow: auto;
}

.cardsList-grid {
  gap: 1rem;
}
</style>
