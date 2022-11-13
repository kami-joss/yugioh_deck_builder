<template>
  <div>
    <modal-spinner v-model="waitingApi" />

    <h1>My Decks</h1>

    <div class="column gap-2">
      <div class="column items-center">
        <decks-filters v-model="filters" />
        <p class="q-my-md">{{ decks.total }} results found</p>
      </div>

      <decks-list :decks="decks.data" />
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
import { api } from "src/boot/axios";
import { useUserStore } from "src/stores/user";
import { ref, onMounted, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import { pickBy } from "lodash";

import DecksList from "src/components/decks/DecksList";
import ModalSpinner from "src/components/modals/ModalSpinner.vue";
import DecksFilters from "src/components/forms/decks/DecksFilters.vue";
import { CLOSING } from "ws";

const user = useUserStore();
const route = useRoute();
const router = useRouter();

const decks = ref({});
const waitingApi = ref(false);
const currentPage = ref(route.query.page ?? 1);

const fetchDecks = async () => {
  await api
    .get(`users/${user.getUser.id}/decks`, {
      params: {
        ...route.query,
      },
    })
    .then((res) => {
      decks.value = res.data;
      waitingApi.value = false;
    });
};

const filters = ref({
  search: "",
  searchBy: "Name",
  illegal: true,
});

watch(
  () => filters,
  async () => {
    const params = pickBy(filters.value, (f) => f != "");
    router.push({
      query: {
        ...route.query,
        ...params,
      },
    });
  },
  { deep: true }
);

watch(
  () => route.query,
  async (val) => {
    await fetchDecks();
  },
  { deep: true }
);

onMounted(async () => {
  waitingApi.value = true;
  await fetchDecks();
});
</script>
