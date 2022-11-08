<template>
  <div>
    <modal-spinner v-model="waitingApi" />

    <div class="row justify-between q-mb-md">
      <q-btn label="Back" icon="arrow_back" flat @click="goBack" />
      <div v-if="userStore.user && deck.user?.id != userStore.user?.id">
        <q-btn
          v-if="userStore.user.favorites?.find((f) => f.id == deck.id)"
          color="pink-8"
          label="Remove from favorites"
          icon="delete"
          @click="removeFromFavorites"
        />
        <q-btn
          v-else
          color="pink-8"
          label="Add to favorites"
          icon="favorite"
          @click="addToFavorites"
        />
        <q-btn label="Clone" icon="content_copy" />
      </div>
    </div>

    <div class="row">
      <div class="col">
        <description-deck :deck="deck" />
      </div>

      <div class="col">
        <deck-list :deck="deck.cards?.main" />
        <hr />
        <deck-list :deck="deck.cards?.extra" />
      </div>

      <div class="col">
        <q-img :src="cardShowing?.image_path" style="width: 250px" />
        <desc-monster-card v-if="cardShowing?.attribute" :card="cardShowing" />
        <desc-spell-card v-else :card="cardShowing" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, onMounted, reactive, ref, watch } from "vue";
import { api } from "boot/axios";
import { useRoute } from "vue-router";
import { useCardStore } from "src/stores/card";
import { useUserStore } from "src/stores/user";
import { isExtraDeck } from "src/utils/cardUtils";
import { useQuasar, Platform } from "quasar";

import DescriptionDeck from "src/components/decks/DescriptionDeck.vue";
import DeckList from "src/components/cards/DeckList.vue";
import DescMonsterCard from "src/components/cards/DescMonsterCard.vue";
import DescSpellCard from "src/components/cards/DescSpellCard.vue";
import ModalSpinner from "src/components/modals/ModalSpinner.vue";

const userStore = useUserStore();

const route = useRoute();

const waitingApi = ref(false);

const $q = useQuasar();

const deck = ref({});
const getDeck = async () => {
  waitingApi.value = true;

  await api.get(`/decks/${route.params.id}`).then((res) => {
    const cards = {
      main: res.data.cards.filter((card) => !isExtraDeck(card.type)),
      extra: res.data.cards.filter((card) => isExtraDeck(card.type)),
      side: [],
    };
    deck.value = { ...res.data, cards };
    waitingApi.value = false;
  });
};

const cardStore = useCardStore();
const cardShowing = ref(cardStore);

cardStore.$subscribe((state) => {
  cardShowing.value = state.payload.card;
});

const goBack = () => {
  window.history.back();
};

const addToFavorites = async () => {
  const userId = userStore.user.id;
  await api
    .post(`/users/${userId}/favorites`, {
      deck_id: route.params.id,
    })
    .then((res) => {
      userStore.setFavorites(res.data);
      $q.notify({
        message: "Deck added to favorites",
        color: "positive",
        position: "top",
        icon: "check",
      });
    });
};

const removeFromFavorites = async () => {
  const userId = userStore.user.id;
  await api
    .delete(`/users/${userId}/favorites`, {
      data: {
        deck_id: route.params.id,
      },
    })
    .then((res) => {
      userStore.setFavorites(res.data);
      $q.notify({
        message: "Deck removed from favorites",
        color: "positive",
        position: "top",
        icon: "check",
      });
    });
};

onMounted(async () => {
  await getDeck();
});
</script>
