<template>
  <div class="row justfiy-center items-start gap-1 ygo-card-container">
    <div class="col" style="width: 250px">
      <q-img :src="card.image_path" spinner-color="black" />
    </div>

    <DescMonsterCard v-if="card.attribute" :card="card" class="col" />
    <DescSpellCard v-else :card="card" class="col" />
  </div>
</template>

<script setup>
import { defineEmits, defineProps } from "vue";
import { useRouter } from "vue-router";
import YgoCard from "./YgoCard.vue";
import DescMonsterCard from "src/components/cards/DescMonsterCard.vue";
import DescSpellCard from "src/components/cards/DescSpellCard.vue";

const props = defineProps({
  card: {
    type: Object,
    required: true,
    default: null,
  },
});
</script>

<style scoped lang="scss">
.ygo-card-container {
  @media (max-width: $breakpoint-md) {
    flex-direction: column;
  }
}
</style>
