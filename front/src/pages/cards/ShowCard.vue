<template>
  <div>
    <div v-if="loading">
      <q-spinner-dots size="100px" color="primary" />
    </div>

    <div v-else class="q-pa-md q-gutter-sm row justify-center">
      <div style="width: 400px">
        <q-img :src="card.image_path" spinner-color="black" />
      </div>
      <desc-monster-card v-if="card.attribute" :card="card" />
      <desc-spell-card
        v-else-if="card.type == 'Spell Card' || 'Trap Card'"
        :card="card"
      />
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { api } from "boot/axios";
import { useRoute } from "vue-router";
import DescMonsterCard from "../../components/cards/DescMonsterCard.vue";
import DescSpellCard from "../../components/cards/DescSpellCard.vue";

const loading = ref(true);
const params = useRoute().params;
const card = ref([]);
const getCard = async () => {
  await api
    .get(`/cards/${params.id}`)
    .then((res) => {
      card.value = res.data;
      loading.value = false;
    })
    .catch((err) => {
      console.log(err);
    });
};
onMounted(async () => {
  await getCard();
});
</script>
