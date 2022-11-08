<template>
  <div>
    <div class="header">
      <q-btn
        label="Save"
        icon="save"
        size="md"
        color="primary"
        @click="modalSaveDeck = true"
      />
      <q-btn
        label="Reset"
        size="md"
        color="primary"
        @click="modalWarnReset = true"
      />
    </div>
    <div v-if="$q.platform.is.desktop" class="row index-row">
      <div class="col-5 deck-container">
        <div class="row justify-between">
          <p class="text-h6">Main Deck</p>
          <p class="text-h6">{{ deck.main.length }}</p>
        </div>
        <deck-list
          :deck="deck.main"
          class="main-deck"
          @remove="removeCardFromDeck"
        />
        <div class="row justify-between">
          <p class="text-h6">Extra Deck</p>
          <p class="text-h6">{{ deck.extra.length }}</p>
        </div>
        <deck-list
          :deck="deck.extra"
          class="extra-deck"
          @remove="removeCardFromDeck"
        />
      </div>
      <cards-list
        :cards="cards.data"
        class="col-4"
        @click:card="addCardToDeck"
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

    <div v-if="$q.platform.is.mobile" class="column col gap-1 lt-md">
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
            @load:scroll="onLoad"
            @click:card="setCardShowing"
          />
        </q-tab-panel>
        <q-tab-panel name="deck">
          <deck-list
            :deck="deck.main"
            class="col-12 col-lg-4"
            @remove="removeCardFromDeck"
            @add="addCardToDeck"
          />
          <deck-list
            :deck="deck.extra"
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

    <q-btn
      v-show="!modalFilters"
      round
      size="lg"
      color="secondary"
      icon="tune"
      class="lt-md btn-filter"
      @click="modalFilters = true"
    />

    <!-- Modales -->
    <modal-ygo-card
      v-model="modalCard"
      :card="cardShowing"
      @add:card="addCardToDeck"
    />

    <modal-save-deck v-model="modalSaveDeck" @save="saveDeck" />

    <modal-confirm
      v-model="modalWarnReset"
      text="Are-you sure to want reset this deck ?"
      @confirm="resetAll"
      @cancel="modalWarnReset = false"
    />

    <modal-spinner v-model="waitingApi" />
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
import ModalYgoCard from "src/components/modals/ModalYgoCard.vue";
import ModalSaveDeck from "src/components/modals/ModalSaveDeck.vue";
import ModalConfirm from "src/components/modals/ModalConfirm.vue";

import { isExtraDeck } from "src/utils/cardUtils";
import ModalSpinner from "src/components/modals/ModalSpinner.vue";

const router = useRouter();
const route = useRoute();
const $q = useQuasar();

const current_page = ref(1);
const cards = ref([]);
const deck = reactive({
  main: [],
  extra: [],
  side: [],
});
const cardShowing = ref(null);
const tab = ref("card");

const modalFilters = ref(false);
const modalCard = ref(false);

const modalSaveDeck = ref(false);

const waitingApi = ref(false);

const saveDeck = (deckOptions) => {
  waitingApi.value = true;
  const cards = [
    ...deck.main.map((card) => card.id),
    ...deck.extra.map((card) => card.id),
    ...deck.side.map((card) => card.id),
  ];
  api
    .post("/decks", {
      ...deckOptions,
      cards,
      public: deckOptions.isPublic,
      user_id: 1,
    })
    .then((res) => {
      waitingApi.value = false;
      $q.notify({
        message: "Deck saved",
        color: "positive",
        position: "top",
        icon: "check",
      });
    })
    .catch((err) => {
      waitingApi.value = false;
      $q.notify({
        message: "An error occured. Please try again later.",
        color: "negative",
        position: "top",
      });
    });
};

const modalWarnReset = ref(false);
const resetAll = () => {
  deck.main = [];
  deck.extra = [];
  deck.side = [];
};

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

const countCopyInDeck = (card) => {
  let count = 0;
  if (isExtraDeck(card.type)) {
    deck.extra.forEach((deckCard) => {
      if (deckCard.id === card.id) {
        count++;
      }
    });
  } else {
    deck.main.forEach((deckCard) => {
      if (deckCard.id === card.id) {
        count++;
      }
    });
  }

  return count;
};

const canAddToDeck = (card) => {
  const main = deck.main.length;
  const extra = deck.extra.length;
  const side = deck.side.length;

  if (main + 1 > 60 && !isExtraDeck(card.type)) {
    $q.notify({
      message: "You can't have more than 60 cards in your main deck",
      color: "negative",
      position: "top",
    });
    return false;
  }

  if (extra + 1 > 15 && isExtraDeck(card.type)) {
    $q.notify({
      message: "You can't have more than 15 cards in your extra deck",
      color: "negative",
      position: "top",
    });
    return false;
  }

  if (side + 1 > 15) {
    $q.notify({
      message: "You can't have more than 15 cards in your side deck",
      color: "negative",
      position: "top",
    });
    return false;
  }

  return true;
};

const addCardToDeck = ({ card, quantity }) => {
  const copies = countCopyInDeck(card);

  if (quantity && canAddToDeck(card)) {
    if (quantity + copies > 3) {
      $q.notify({
        message: `You can't have more than 3 of the same card in your deck. Number in deck: ${copies}`,
        color: "amber-6",
        position: "top",
        icon: "warning",
      });
    } else {
      if (isExtraDeck(card.type)) {
        for (let i = 0; i < quantity; i++) {
          deck.extra.push(card);
        }
      } else {
        for (let i = 0; i < quantity; i++) {
          deck.main.push(card);
        }
      }
    }
    return;
  }
};

const setCardShowing = (card) => {
  cardShowing.value = card;

  if ($q.platform.is.mobile) {
    modalCard.value = true;
  }
};

const removeCardFromDeck = (card) => {
  if (isExtraDeck(card.type)) {
    let index = deck.extra.indexOf(deck.extra.find((c) => c.id === card.id));
    deck.extra.splice(index, 1);
  } else {
    let index = deck.main.indexOf(deck.main.find((c) => c.id === card.id));
    deck.main.splice(index, 1);
  }
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
  if (route.params.id) {
    waitingApi.value = true;
    await api
      .get(`/decks/${route.params.id}/edit`)
      .then((res) => {
        waitingApi.value = false;
        res.data.cards.forEach((card) => {
          if (isExtraDeck(card.type)) {
            deck.extra.push(card);
          } else {
            deck.main.push(card);
          }
        });
      })
      .catch((err) => {
        waitingApi.value = false;
        switch (err.response.status) {
          case 403:
            $q.notify({
              message: "You don't have the permission to edit this deck",
              color: "negative",
              position: "top",
            });
            router.replace("/");
            break;
        }
      });
  }
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
  justify-content: space-between;
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

.deck-container {
  max-height: 90vh;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.main-deck {
  max-height: 50%;
  overflow-y: auto;
}

.extra-deck {
  max-height: 25%;
  overflow-y: auto;
}

.custom {
  @media (max-width: $breakpoint-md) {
    display: none;
  }
}

.header {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  margin: 1rem;
}
</style>
