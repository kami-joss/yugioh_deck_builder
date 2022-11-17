<template>
  <div>
    <modal-spinner v-model="waitingApi" />
    <modal-save-deck v-model="modalClone" @save="onClone" />
    <modal-confirm
      v-model="modalDelete"
      text="Are you sur to want delete this deck ?"
      @confirm="deleteDeck"
    />

    <div v-if="deck">
      <div class="header">
        <q-btn label="Back" icon="arrow_back" flat @click="router.back()" />
        <div v-if="userStore.getUser" class="row items-center gap-1">
          <div v-if="deck.user?.id != userStore.getUser?.id" class="row gap-1">
            <q-btn
              v-if="userStore.getUser.favorites?.find((f) => f.id == deck.id)"
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
          </div>

          <div v-if="deck.user?.id == userStore.getUser?.id" class="row gap-1">
            <q-btn
              label="Edit"
              icon="edit"
              color="primary"
              @click="router.push(`/decks/edit/${deck.id}`)"
            />
            <q-btn
              label="Delete"
              icon="delete"
              color="negative"
              @click="modalDelete = true"
            />
          </div>

          <q-btn label="Clone" icon="content_copy" @click="modalClone = true" />
        </div>
      </div>

      <div class="deckview-container row justify-between">
        <div class="col-md-3">
          <description-deck :deck="deck" />
        </div>

        <div class="col-md-4">
          <p class="text-h6">Main deck ({{ deck.cards?.main.length }})</p>
          <deck-list :deck="deck.cards?.main" />
          <hr />
          <p class="text-h6">Extra deck ({{ deck.cards?.extra.length }})</p>
          <deck-list :deck="deck.cards?.extra" />
        </div>

        <div v-if="$q.platform.is.desktop" class="col-md-4">
          <q-img :src="cardShowing?.image_path" style="width: 250px" />
          <desc-monster-card
            v-if="cardShowing?.attribute"
            :card="cardShowing"
          />
          <desc-spell-card v-else :card="cardShowing" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, onMounted, reactive, ref, watch } from "vue";
import { api } from "boot/axios";
import { useRoute, useRouter } from "vue-router";
import { useCardStore } from "src/stores/card";
import { useUserStore } from "src/stores/user";
import { isExtraDeck } from "src/utils/cardUtils";
import { useQuasar, Platform } from "quasar";

import DescriptionDeck from "src/components/decks/DescriptionDeck.vue";
import DeckList from "src/components/cards/DeckList.vue";
import DescMonsterCard from "src/components/cards/DescMonsterCard.vue";
import DescSpellCard from "src/components/cards/DescSpellCard.vue";
import ModalSpinner from "src/components/modals/ModalSpinner.vue";
import ModalSaveDeck from "src/components/modals/ModalSaveDeck.vue";
import ModalConfirm from "src/components/modals/ModalConfirm.vue";

const userStore = useUserStore();

const route = useRoute();
const router = useRouter();

const waitingApi = ref(false);

const $q = useQuasar();

const deck = ref(null);
const getDeck = async () => {
  waitingApi.value = true;

  await api.get(`/decks/${route.params.id}`).then((res) => {
    const cards = {
      main: res.data.main_deck,
      extra: res.data.extra_deck,
      side: res.data.side_deck,
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

const addToFavorites = async () => {
  const userId = userStore.getUser.id;

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
  const userId = userStore.getUser.id;
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

const modalClone = ref(false);
const onClone = async (deckOptions) => {
  const userId = userStore.getUser.id;
  await api
    .post(`/decks/${route.params.id}/clone`, {
      ...deckOptions,
      public: deckOptions.isPublic,
      user_id: userId,
    })
    .then((res) => {
      $q.notify({
        message: "Deck cloned",
        color: "positive",
        position: "top",
        icon: "check",
      });
      modalClone.value = false;
      router.push(`/decks/edit/${res.data.id}`);
    });
};

const modalDelete = ref(false);
const deleteDeck = async () => {
  await api.delete(`/decks/${route.params.id}`).then((res) => {
    modalDelete.value = false;
    $q.notify({
      message: "Deck deleted",
      color: "positive",
      position: "top",
      icon: "check",
    });
    router.replace("/decks");
  });
};

watch(
  () => route.params.id,
  () => {
    getDeck();
  }
);

onMounted(async () => {
  await getDeck();
});
</script>

<style scoped lang="scss">
.deckview-container {
  @media (max-width: $breakpoint-md) {
    flex-direction: column;
    gap: 20px;
  }
}

.header {
  display: flex;
  flex-wrap: wrap;
  margin-bottom: 20px;
  justify-content: space-between;
  @media screen and (max-width: $breakpoint-md) {
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 20px;
  }
}
</style>
