<template>
  <div>
    <div class="row index-row">
      <div class="col-12 col-lg row inline deckbox">
        <ygo-card
          v-for="card in deck"
          :key="card.id"
          :card="card"
          :clickable="true"
          @click="removeCardFromDeck"
          class="col-xs-12 col-sm-6 col-md-4 col-lg-2 col"
        />
      </div>

      <div class="col-12 col-lg cardsList-container" ref="scrollTargetRef">
        <q-infinite-scroll
          @load="onLoad"
          :offset="250"
          :scroll-target="scrollTargetRef"
        >
          <div class="row inline justify-center cardsList-grid">
            <ygo-card
              v-for="card in cards"
              :key="card.id"
              :card="card"
              :clickable="true"
              @click="addCardToDeck"
              class="col-xs-12 col-sm-6 col-md-4 col-lg-2 col"
            />
          </div>
          <template v-slot:loading>
            <div class="row justify-center q-my-md">
              <q-spinner-dots color="primary" size="40px" />
            </div>
          </template>
        </q-infinite-scroll>
      </div>

      <div class="col-lg-3">
        <filters-cards @search="onSearch" />
      </div>
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

const router = useRouter();
const route = useRoute();
const $q = useQuasar();
const scrollTargetRef = ref(null);

const current_page = ref(1);
const cards = ref([]);

const deck = ref([]);

const getCards = async (params) => {
  const queryParams = route.query;
  await api
    .get("/cards", {
      params: {
        ...queryParams,
        ...params,
      },
    })
    .then((res) => {
      console.log(res.data.data);
      cards.value = cards.value.concat(res.data.data);
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
  setTimeout(() => {
    current_page.value++;
    getCards({ page: current_page.value });
    done();
  }, 2000);
};

const onSearch = (form) => {
  console.log(form);
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
  gap: 3rem;
  @media (max-width: $breakpoint-md) {
    flex-direction: column-reverse;
  }
}

.deckbox {
  border: 1px solid #ccc;
  border-radius: 5px;
  padding: 1rem;
  gap: 1rem;
  max-height: 100vh;
}

.cardsList-grid {
  gap: 1rem;
}

.cardsList-container {
  max-height: 100vh;
  overflow: auto;
}
</style>
