<template>
  <div>
    <p class="text-h6">{{ label }}</p>
    <div class="row justify-between">
      <q-input v-model="form.levels.min" type="number" outlined />
      <q-input v-model="form.levels.max" type="number" outlined />
    </div>
    <q-range
      v-model="form.levels"
      marker-labels
      :min="0"
      :max="12"
      @change="emits('update:levels', form.levels)"
    />
  </div>
</template>

<script setup>
import { reactive, defineProps, defineEmits } from "vue";

const emits = defineEmits(["update:levels"]);

const props = defineProps({
  levels: {
    type: Object,
    default: () => ({ min: 0, max: 12 }),
  },
  label: {
    type: String,
    default: "Levels",
  },
});

const form = reactive({
  levels: props.levels,
});
</script>
