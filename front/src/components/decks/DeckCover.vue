<template>
  <q-card
    class="deck-cover"
    @click="router.push(`/decks/${deck.id}`)"
    @mouseover="onOverDeck"
    @mouseleave="onLeaveDeck"
  >
    <q-card-section>
      <p>
        <span class="text-bold"> {{ deck.name }} </span><br />
        <span class="text-secondary"> By {{ deck.user?.name }}</span>
      </p>
      <q-card-action v-if="deck.illegal">
        <q-chip
          color="negative"
          text-color="white"
          label="Forbidden"
          dense
          outline
        />
      </q-card-action>
    </q-card-section>
    <q-card-section>
      <div class="image_container">
        <q-img class="img" :src="deck.image_path" />
      </div>
    </q-card-section>
    <q-card-action
      v-if="buttons.length > 0"
      @click.stop="modalDelete = true"
      class="bg-primary"
    >
      <q-btn
        v-if="buttons.includes('delete')"
        flat
        icon="delete"
        style="width: 100%"
      />
    </q-card-action>

    <!-- Modales -->
    <modal-confirm
      v-model="modalDelete"
      text="Are you sur to want delete this deck ?"
      @confirm="deleteDeck"
    />
    <modal-spinner v-model="waitingApi" />
  </q-card>
</template>

<script setup>
import { defineProps, defineEmits, ref } from "vue";
import { useUserStore } from "src/stores/user";
import { api } from "boot/axios";
import { useRoute, useRouter } from "vue-router";
import { useQuasar } from "quasar";
import { useDeckStore } from "src/stores/deck";
import { debounce } from "lodash";

import ModalConfirm from "src/components/modals/ModalConfirm.vue";
import ModalSpinner from "../modals/ModalSpinner.vue";

const userStore = useUserStore();
const route = useRoute();
const router = useRouter();
const $q = useQuasar();
const deckStore = useDeckStore();

const waitingApi = ref(false);

const props = defineProps({
  deck: {
    type: Object,
    required: true,
  },
  buttons: {
    type: Array,
    default: () => [],
  },
});

const emits = defineEmits(["delete"]);

const onOverDeck = () => {
  $q.platform.is.desktop && setDeck();
};

const onLeaveDeck = () => {
  setDeck.cancel();
};

const setDeck = debounce(() => {
  deckStore.setDeck(props.deck);
}, 500);

const modalDelete = ref(false);

const deleteDeck = async () => {
  modalDelete.value = false;
  waitingApi.value = true;
  await api.delete(`/decks/${props.deck.id}`).then((res) => {
    waitingApi.value = false;
    router.go();
    $q.notify({
      message: "Deck deleted",
      color: "positive",
      position: "top",
      icon: "check",
    });
  });
};
</script>

<style scoped lang="scss">
.image_container {
  position: relative;
  width: 100%;
}

.img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.deck-cover {
  cursor: pointer;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.deck-cover:hover {
  background-color: rgb(245, 245, 245, 0.5);
  transition-duration: 200ms;
}
</style>
