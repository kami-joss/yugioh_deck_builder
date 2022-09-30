<template>
  <div>
    <div class="row index-row">
      <div class="col-12 col-lg row inline">
        <ygo-card
          v-for="card in cards.data"
          :key="card.id"
          :card="card"
          class="col-xs-12 col-sm-6 col-md-4 col-lg-2 col-xl-1"
        />
      </div>
      <div class="col-lg-3 order-md-last">
        <filters-cards @search="onSearch" />
      </div>
    </div>

    <div class="row justify-center">
      <q-pagination
        v-model="current_page"
        :max-pages="5"
        :max="cards.last_page"
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

import FiltersCards from "../components/forms/filters/FiltersCards.vue";
import YgoCard from "../components/cards/YgoCard.vue";

const router = useRouter();
const route = useRoute();

const current_page = ref(1);
const cards = ref([]);

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
      cards.value = res.data;
    })
    .catch((err) => {
      console.error(err);
    });
};

watch(
  () => route.query,
  async (params) => {
    await getCards(params);
  }
);

watch(current_page, (val) => {
  router.push({
    query: {
      ...route.query,
      page: val,
    },
  });
  // await getCards();
});

const onSearch = (form) => {
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
</style>
