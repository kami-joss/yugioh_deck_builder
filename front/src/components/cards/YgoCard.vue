<template>
  <div class="column items-center ygo-card" @click="redirect">
    <div style="width: 120px">
      <q-img :src="card.image_path" spinner-color="black" />
    </div>
    <q-separator spaced dark />
    <p class="text-center">
      {{ card.name }}
    </p>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();

const props = defineProps({
  card: {
    type: Object,
    required: true,
  },
});

const redirect = () => {
  router.push(`/card/${props.card.id}`);
};
</script>

<style scoped>
.ygo-card {
  cursor: pointer;
}
</style>
