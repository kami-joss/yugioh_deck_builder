<template>
  <div>
    <div class="row index-row gt-md">
      <deck-list
        :deck="deck"
        class="col-12 col-lg-4"
        @remove="removeCardFromDeck"
        @add="addCardToDeck"
      />
      <cards-list
        :cards="cards.data"
        class="col-12 col-lg-4"
        @click="addCardToDeck"
        @load:scroll="onLoad"
        @hover:card="setCardShowing"
      />

      <q-card class="column col gap-1 gt-md">
        <q-tabs
          v-model="tab"
          dense
          class="text-grey"
          active-color="primary"
          indicator-color="primary"
          align="justify"
          narrow-indicator
          inline-label
        >
          <q-tab name="card" label="Description" icon="card" />
          <q-tab name="filters" label="Search" icon="search" />
        </q-tabs>

        <q-tab-panels v-model="tab" animated>
          <q-tab-panel name="filters">
            <filters-cards @search="onSearch" />
          </q-tab-panel>
          <q-tab-panel name="card">
            <full-ygo-card
              v-if="cardShowing"
              :card="cardShowing"
              :key="cardShowing.id"
              class="z-10"
            />
          </q-tab-panel>
        </q-tab-panels>
      </q-card>
    </div>

    <!-- View on mobile -->

    <div class="column col gap-1 lt-md">
      <q-tabs
        v-model="tab"
        dense
        class="text-grey"
        active-color="primary"
        indicator-color="primary"
        align="justify"
        narrow-indicator
        inline-label
      >
        <q-tab name="cardList" label="List" icon="search" />
        <q-tab name="deck" label="Deck" icon="" />
      </q-tabs>

      <q-tab-panels v-model="tab" animated>
        <q-tab-panel name="cardList">
          <cards-list
            :cards="cards.data"
            class="col-12 col-lg-4"
            @click="addCardToDeck"
            @load="onLoad"
            @hover:card="setCardShowing"
          />
        </q-tab-panel>
        <q-tab-panel name="deck">
          <deck-list
            :deck="deck"
            class="col-12 col-lg-4"
            @remove="removeCardFromDeck"
            @add="addCardToDeck"
          />
        </q-tab-panel>
      </q-tab-panels>
    </div>

    <q-dialog v-model="modalFilters">
      <q-btn
        round
        size="md"
        color="red-5"
        icon="close"
        class="lt-md btn-filter-close"
        @click="modalFilters = false"
      />
      <filters-cards @search="onSearch" />
    </q-dialog>

    <q-dialog v-model="modalCard">
      <q-btn
        round
        size="md"
        color="red-5"
        icon="close"
        class="lt-md btn-filter-close"
        @click="modalCard = false"
      />
      <full-ygo-card
        v-if="cardShowing"
        :card="cardShowing"
        :key="cardShowing.id"
        class="z-10"
      />
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
</template>

<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import { api } from "boot/axios";
import { useRoute, useRouter } from "vue-router";
import { useQuasar, Platform } from "quasar";

import FiltersCards from "src/components/forms/filters/FiltersCards.vue";
import YgoCard from "src/components/cards/YgoCard.vue";
import CardsList from "src/components/cards/CardsList.vue";
import DeckList from "src/components/cards/DeckList.vue";
import FullYgoCard from "src/components/cards/FullYgoCard.vue";

const router = useRouter();
const route = useRoute();
const $q = useQuasar();

const current_page = ref(1);
const cards = ref([]);
const deck = ref([]);
const cardShowing = ref(null);
const tab = ref("card");
const modalFilters = ref(false);
const modalCard = ref(false);

const getCards = async (params, load, done) => {
  const queryParams = route.query;
  await api
    .get("/cards", {
      params: {
        ...queryParams,
        ...params,
      },
    })
    .then((res) => {
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

const addCardToDeck = (card) => {
  let cardExists = 0;

  deck.value.forEach((deckCard) => {
    if (deckCard.id === card.id) {
      cardExists++;
    }
  });

  if (cardExists === 3) {
    $q.notify({
      message: "You can't have more than 3 of the same card in your deck",
      color: "amber-6",
      position: "top",
      icon: "warning",
    });
    return;
  }

  deck.value.push(card);
};

const setCardShowing = (card) => {
  cardShowing.value = card;
};

const removeCardFromDeck = (card) => {
  let index = deck.value.indexOf(deck.value.find((c) => c.id === card.id));
  deck.value.splice(index, 1);
};

const onLoad = (index, done) => {
  if (current_page.value > cards.value.last_page) {
    done();
    return;
  }
  setTimeout(async () => {
    current_page.value++;
    await getCards({ page: current_page.value }, true);
    done();
  }, 2000);
};

const onSearch = (form) => {
  current_page.value = 1;
  tab.value = "card";
  router.push({
    query: {
      ...route.query,
      ...form,
    },
  });
};

onMounted(async () => {
  await getCards();
});

watch(
  () => route.query,
  async (params) => {
    await getCards(params);
  }
);
</script>

<style scoped lang="scss">
.index-row {
  gap: 1rem;
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

.btn-filter-close {
  position: fixed;
  top: 1rem;
  right: 1rem;
  z-index: 100;
}
</style>
