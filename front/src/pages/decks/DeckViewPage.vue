<template>
  <div>
    <modal-spinner v-model="waitingApi" />

    <div class="row justify-between q-mb-md">
      <q-btn label="Retour" icon="arrow_back" @click="goBack" />
      <div>
        <q-btn
          class=""
          color="pink-8"
          label="Ajouter aux favoris"
          icon="favorite"
          @click="addToFavorites"
        />
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
import { isExtraDeck } from "src/utils/cardUtils";

import DescriptionDeck from "src/components/decks/DescriptionDeck.vue";
import DeckList from "src/components/cards/DeckList.vue";
import DescMonsterCard from "src/components/cards/DescMonsterCard.vue";
import DescSpellCard from "src/components/cards/DescSpellCard.vue";
import ModalSpinner from "src/components/modals/ModalSpinner.vue";

const route = useRoute();
const waitingApi = ref(false);

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

const CardStore = useCardStore();
const cardShowing = ref(CardStore);

CardStore.$subscribe((state) => {
  cardShowing.value = state.payload.card;
});

const goBack = () => {
  window.history.back();
};

const addToFavorites = async () => {
  waitingApi.value = true;

  const data = await api
    .post(`/decks/${route.params.id}/favorites`)
    .then((res) => {
      waitingApi.value = false;
      return res.data;
    });
  deck.value = data;
};

onMounted(async () => {
  await getDeck();
});
</script>
