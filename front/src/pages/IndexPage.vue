<template>
  <div>
    <filters-cards @search="onSearch" />
    <div class="row justify-center">
      <div
        v-for="card in cards.data"
        :key="card.id"
        class="col-5 col-sm-3 col-md-2 q-ma-xs q-pa-xs index-card"
      >
        <a :href="`#/cards/${card.id}`" class="column items-center">
          <div style="width: 120px">
            <q-img :src="card.image_small?.path" spinner-color="black" />
          </div>
          <q-separator spaced dark />
          <p class="text-center">
            {{ card.name }}
          </p>
        </a>
      </div>
      <div></div>
    </div>
    <q-pagination
      v-model="current_page"
      :max-pages="5"
      :max="cards.last_page"
      boundary-links
      boundary-number
    />
  </div>
</template>

<script setup>
import { onMounted, ref, watch } from "vue";
import { api } from "boot/axios";
import { useRoute } from "vue-router";
import FiltersCards from "../components/forms/FiltersCards.vue";

const params = useRoute().params;

const current_page = ref(1);
const cards = ref([]);
const getCards = async (params) => {
  await api
    .get("/cards", {
      params: {
        page: current_page.value,
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

watch(current_page, async () => {
  await getCards();
});

const onSearch = async (form) => {
  await getCards(form);
};

onMounted(async () => {
  await getCards();
});
</script>
