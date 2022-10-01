<template>
  <div>
    <div class="row index-row">
      <deck-list
        :deck="deck"
        class="col-12 col-lg-4"
        @remove="removeCardFromDeck"
        @add="addCardToDeck"
      />
      <cards-list
        :cards="cards.data"
        class="col-12 col-lg-5"
        @click="addCardToDeck"
        @load="onLoad"
      />
      <filters-cards class="col" @search="onSearch" />

      <div class=""></div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import { api } from "boot/axios";
import { useRoute, useRouter } from "vue-router";
import { useQuasar } from "quasar";

import FiltersCards from "src/components/forms/filters/FiltersCards.vue";
import YgoCard from "src/components/cards/YgoCard.vue";
import CardsList from "src/components/cards/CardsList.vue";
import DeckList from "src/components/cards/DeckList.vue";

const router = useRouter();
const route = useRoute();
const $q = useQuasar();
const scrollTargetRef = ref(null);

const current_page = ref(1);
const cards = ref([]);

const deck = ref([]);

const getCards = async (params, load) => {
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

const removeCardFromDeck = (card) => {
  let index = deck.value.indexOf(deck.value.find((c) => c.id === card.id));
  deck.value.splice(index, 1);
};

watch(
  () => route.query,
  async (params) => {
    await getCards(params);
  }
);

const onLoad = (index, done) => {
  if (current_page.value > cards.value.last_page) {
    done();
    return;
  }
  setTimeout(() => {
    current_page.value++;
    getCards({ page: current_page.value }, true);
    done();
  }, 2000);
};

const onSearch = (form) => {
  current_page.value = 1;
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
</script>

<style scoped lang="scss">
.index-row {
  gap: 1rem;
  @media (max-width: $breakpoint-md) {
    flex-direction: column-reverse;
  }
}
</style>
