<template>
  <q-card class="my-card" @click="router.push(`/decks/${deck.id}`)">
    <q-card-section>
      <q-item>
        <q-item-section>
          <q-item-label> {{ deck.name }} </q-item-label>
          <q-item-label caption> By {{ deck.user?.name }} </q-item-label>
        </q-item-section>
      </q-item>
      <q-chip
        v-if="deck.illegal"
        color="negative"
        text-color="white"
        label="Forbidden"
      />
    </q-card-section>
    <div class="image_container">
      <q-img
        :src="deck.image_path"
        spinner-color="black"
        style="width: 100%; position: relative"
      />
    </div>
    <q-item-section v-if="buttons.length > 0" @click.stop="modalDelete = true">
      <q-btn v-if="buttons.includes('delete')" flat icon="delete" />
    </q-item-section>

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

import ModalConfirm from "src/components/modals/ModalConfirm.vue";
import ModalSpinner from "../modals/ModalSpinner.vue";

const userStore = useUserStore();
const route = useRoute();
const router = useRouter();
const $q = useQuasar();

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
  max-height: 200px;
  overflow: hidden;
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>
