<template>
  <div>
    <modal-spinner v-model="waitingApi" />

    <h1 class="text-h4 text-center">My Decks</h1>

    <div class="column gap-2">
      <div class="column items-center">
        <decks-filters v-model="filters" />
        <p class="q-my-md">{{ decks.total }} results found</p>
      </div>
    </div>
    <div class="row justify-between">
      <decks-list :decks="decks.data" class="col-12 col-md-8" />
      <div
        v-if="$q.platform.is.desktop"
        class="col-4 description-deck-container"
      >
        <description-deck v-if="deckShowing" :deck="deckShowing" />
      </div>
    </div>
    <div class="row justify-center q-my-md">
      <q-pagination
        v-model="currentPage"
        :max-pages="5"
        :max="decks.last_page ?? 1"
        boundary-links
        boundary-number
      />
    </div>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import { api } from "boot/axios";
import { useRoute, useRouter } from "vue-router";
import { useQuasar, Platform } from "quasar";
import { useDeckStore } from "src/stores/deck";
import { useUserStore } from "src/stores/user";

import DecksFilters from "src/components/forms/decks/DecksFilters.vue";
import DeckCover from "src/components/decks/DeckCover.vue";
import ModalSpinner from "src/components/modals/ModalSpinner.vue";
import DecksList from "src/components/decks/DecksList.vue";
import DescriptionDeck from "src/components/decks/DescriptionDeck.vue";

const router = useRouter();
const route = useRoute();
const deckStore = useDeckStore();
const userStore = useUserStore();

const deckShowing = ref(null);

deckStore.$subscribe((state) => {
  console.log(state);
  deckShowing.value = state.events.newValue;
});

const waitingApi = ref(false);

const decks = ref([]);
const getDecks = async () => {
  waitingApi.value = true;
  await api
    .get(`users/${userStore.getUser.id}/decks`, {
      params: {
        ...route.query,
      },
    })
    .then((res) => {
      decks.value = res.data;
      waitingApi.value = false;
    });
};

watch(
  () => route.query,
  async (val) => {
    await getDecks();
  },
  { deep: true }
);

const currentPage = ref(1);
watch(currentPage, async (val) => {
  router.push({
    query: {
      ...route.query,
      page: val,
    },
  });
  await getDecks();
});

const filters = ref({
  search: "",
  searchBy: "Name",
  illegal: true,
});

watch(
  () => filters.value,
  async (val) => {
    router.push({
      query: {
        ...route.query,
        search: filters.value.search,
        searchBy: filters.value.searchBy,
        illegal: filters.value.illegal,
      },
    });
  },
  { deep: true }
);

onMounted(async () => {
  await getDecks();
});
</script>

<style scoped>
.deck-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  flex-wrap: wrap;
  gap: 1rem;
}

.description-deck-container {
  position: fixed;
  right: 0;
}
</style>
