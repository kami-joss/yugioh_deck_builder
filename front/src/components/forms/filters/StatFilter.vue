<template>
  <div>
    <p class="text-h6">{{ label }}</p>
    <div class="row justify-between">
      <q-input
        v-model="form.min"
        type="number"
        outlined
        @update:model-value="emits('update:min', form.min)"
      />
      <q-input
        v-model="form.max"
        type="number"
        outlined
        @update:model-value="emits('update:max', form.max)"
      />
    </div>
  </div>
</template>

<script setup>
import { reactive, defineEmits, watch, defineProps } from "vue";

const props = defineProps({
  max: {
    type: Number,
    required: true,
    default: 0,
  },
  min: {
    type: Number,
    required: true,
    default: 0,
  },
  label: {
    type: String,
    default: "Stat",
  },
});
const emits = defineEmits(["update:max", "update:min"]);
const form = reactive({
  max: props.max,
  min: props.min,
});
</script>
