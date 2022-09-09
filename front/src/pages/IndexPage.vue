<template>
  <div class="row">
    <div v-for="card in cards.data" :key="card.id" class="col-2">
      <a :href="`#/cards/${card.id}`">
        <div style="width: 100px">
          <q-img :src="card.image?.path" spinner-color="black" />
        </div>
        <p>
          {{ card.name }}
        </p>
      </a>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { api } from "boot/axios";

const cards = ref([]);

const getCards = async () => {
  await api
    .get("/cards")
    .then((res) => {
      cards.value = res.data;
    })
    .catch((err) => {
      console.log(err);
    });
};

onMounted(async () => {
  await getCards();
});
</script>
