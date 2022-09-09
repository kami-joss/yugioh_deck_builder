<template>
  <div class="q-pa-md q-gutter-sm row justify-center">
    <div style="width: 400px">
      <q-img :src="card.image?.path" spinner-color="black" />
    </div>
    <card-monster-card v-if="card.attribute" :card="card" />
  </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { api } from "boot/axios";
import { useRoute } from "vue-router";
import CardMonsterCard from "components/cards/CardMonsterCard.vue";

const params = useRoute().params;
const card = ref([]);
const loading = true;
const path = "";
const getCard = async () => {
  await api
    .get(`/cards/${params.id}`)
    .then((res) => {
      card.value = res.data;
      loading = false;
    })
    .catch((err) => {
      console.log(err);
    });
};
onMounted(async () => {
  await getCard();
});
</script>
