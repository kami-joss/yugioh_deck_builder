<template>
  <div class="ygo-card" @click="handleClick" @mouseover="handleHover">
    <div style="width: 100%; position: relative">
      <q-img :src="card.image_path" spinner-color="black" />
      <q-icon
        v-if="card.number_allowed == 0"
        name="block"
        color="negative"
        class="icon-forbidden"
        size="2rem"
      >
        <q-tooltip
          class="bg-red"
          anchor="bottom middle"
          self="top middle"
          :offset="[10, 10]"
        >
          <strong> Forbidden </strong>
        </q-tooltip>
      </q-icon>
      <div
        v-if="card.number_allowed == 2 || card.number_allowed == 1"
        class="icon-warning"
      >
        {{ card.number_allowed }}
        <q-tooltip
          class="bg-warning"
          anchor="bottom middle"
          self="top middle"
          :offset="[10, 10]"
        >
          <strong>
            {{ card.number_allowed == 1 ? "Limited" : "Semi-Limited" }}
          </strong>
        </q-tooltip>
      </div>
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

<style scoped lang="scss">
.ygo-card {
  cursor: pointer;
  max-height: 200px;
}
.icon-forbidden {
  position: absolute;
  top: 0;
  right: 0;
  font-size: 1.5rem;
}

.icon-warning {
  position: absolute;
  top: 0;
  right: 0;
  padding: 5px;
  color: white;
  font-weight: bold;
  border-radius: 500px;
  background-color: $amber-6;
}
</style>
