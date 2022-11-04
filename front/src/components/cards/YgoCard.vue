<template>
  <div class="ygo-card" @click="handleClick" @mouseover="handleHover">
    <div style="width: 100%; position: relative">
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
import { defineEmits, defineProps, ref, watch } from "vue";
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
  cardSelected: {
    type: Object,
    default: null,
  },
});

const emits = defineEmits(["click", "hover:card", "add"]);
const options = ref(false);

const handleClick = () => {
  if (props.clickable) {
    emits("click", props.card);
  }
  // router.push(`/card/${props.card.id}`);
};
const handleHover = () => {
  emits("hover:card", props.card);
};

watch(
  () => props.cardSelected,
  (carte) => {
    carte.id == props.card.id
      ? (options.value = true)
      : (options.value = false);
  }
);
</script>

<style scoped>
.ygo-card {
  cursor: pointer;
  max-height: 200px;
}
</style>
