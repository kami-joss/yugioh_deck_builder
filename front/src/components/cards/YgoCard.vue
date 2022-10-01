<template>
  <div class="ygo-card" @click="handleClick" @mouseover="handleHover">
    <div style="width: 100%">
      <q-img :src="card.image_path" spinner-color="black" />
    </div>
    <div v-if="label">
      <q-separator spaced dark />
      <p class="text-center">
        {{ card.name }}
      </p>
    </div>
  </div>
</template>

<script setup>
import { defineEmits, defineProps } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();

const props = defineProps({
  card: {
    type: Object,
    required: true,
  },
  label: {
    type: String,
    default: null,
  },
  clickable: {
    type: Boolean,
    default: false,
  },
});

const emits = defineEmits(["click", "hover:card"]);

const handleClick = () => {
  if (props.clickable) {
    emits("click", props.card);
  }
  // router.push(`/card/${props.card.id}`);
};
const handleHover = () => {
  emits("hover:card", props.card);
};
</script>

<style scoped>
.ygo-card {
  cursor: pointer;
  max-height: 200px;
}
</style>
