<template>
  <div>
    <modal-spinner v-model="waitingApi" />

    <h1>Decks</h1>

    <div class="column gap-2">
      <div class="column items-center">
        <decks-filters v-model="filters" />
        <p class="q-my-md">{{ decks.total }} results found</p>
      </div>
      <div class="row gap-1 justify-center">
        <deck-cover
          v-for="deck in decks.data"
          :deck="deck"
          :key="deck.id"
          class="col-sm-5 col-md-2 col-lg-1"
        />
      </div>
    </div>

    <div class="row justify-center q-my-lg">
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

import DecksFilters from "src/components/forms/decks/DecksFilters.vue";
import DeckCover from "src/components/decks/DeckCover.vue";
import ModalSpinner from "src/components/modals/ModalSpinner.vue";

const router = useRouter();
const route = useRoute();

const waitingApi = ref(false);

const decks = ref([]);
const getDecks = async () => {
  waitingApi.value = true;
  await api
    .get("/decks", {
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
  () => filters.value.search,
  async (val) => {
    router.push({
      query: {
        ...route.query,
        search: val,
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
</style>
