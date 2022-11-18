<template>
  <div>
    <div v-if="$q.platform.is.desktop">
      <div class="row justify-between index-row">
        <div class="col-lg-4">
          <cards-list
            v-if="cards.data"
            :cards="cards.data"
            class="col-4"
            @load:scroll="onLoad"
          />
        </div>
        <div class="col-4">
          <full-ygo-card
            v-if="cardShowing"
            :card="cardShowing"
            :key="cardShowing.id"
            @click:show-decks="modalDecks = true"
          />
        </div>
        <div class="order-md-last col-3">
          <filters-cards @search="onSearch" />
        </div>
      </div>

      <q-dialog v-if="cardShowing" v-model="modalDecks">
        <mini-decks-list
          :decks="cardShowing?.decks?.filter((deck) => deck.public)"
        />
      </q-dialog>
    </div>

    <!-- Vue mobile -->
    <div v-if="$q.platform.is.mobile">
      <cards-list
        v-if="cards.data"
        :cards="cards.data"
        class="col-4"
        @load:scroll="onLoad"
      />

      <q-dialog v-model="modalFilters">
        <filters-cards @search="onSearch" />
      </q-dialog>

      <q-btn
        v-show="!modalFilters"
        round
        size="lg"
        color="secondary"
        icon="tune"
        class="lt-md btn-filter"
        @click="modalFilters = true"
      />
    </div>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import { api } from "boot/axios";
import { useRoute, useRouter } from "vue-router";
import { useCardStore } from "src/stores/card";

import FiltersCards from "../components/forms/filters/FiltersCards.vue";
import CardsList from "src/components/cards/CardsList.vue";
import FullYgoCard from "src/components/cards/FullYgoCard.vue";
import MiniDecksList from "src/components/decks/MiniDecksList.vue";

const router = useRouter();
const route = useRoute();

const current_page = ref(route.query.page ?? 1);
const cards = ref([]);
const cardStore = useCardStore();
const cardShowing = ref(null);
const modalDecks = ref(false);
const modalFilters = ref(false);
const tab = ref("card");

cardStore.$subscribe((state) => {
  cardShowing.value = state.payload.card;
});

const getCards = async (params, load) => {
  await api
    .get("/cards", {
      params: {
        ...params,
      },
    })
    .then((res) => {
      if (!cardShowing.value) {
        cardShowing.value = res.data.data[0];
      }
      if (load) {
        cards.value.data = [...cards.value.data, ...res.data.data];
      } else {
        cards.value = res.data;
      }
    })
    .catch((err) => {
      console.error(err);
    });
};

watch(
  () => route.query,
  async (params) => {
    await getCards({ ...params, page: current_page.value });
  }
);

// watch(current_page, async (val) => {
//   router.push({
//     query: {
//       ...route.query,
//       page: val,
//     },
//   });
// });

const onLoad = (index, done) => {
  if (current_page.value > cards.value.last_page) {
    done();
    return;
  }
  setTimeout(async () => {
    current_page.value++;
    await getCards({ ...route.query, page: current_page.value }, true);
    done();
  }, 1000);
};

const onSearch = (form) => {
  current_page.value = 1;
  router.push({
    query: {
      ...form,
    },
  });
};

onMounted(async () => {
  await getCards();
});
</script>

<style scoped lang="scss">
.index-row {
  @media (max-width: $breakpoint-md) {
    flex-direction: column-reverse;
  }
}

.btn-filter {
  position: fixed;
  bottom: 1rem;
  right: 1rem;
  z-index: 100;
}
</style>
