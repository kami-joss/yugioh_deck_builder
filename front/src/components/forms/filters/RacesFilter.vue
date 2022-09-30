<template>
  <div>
    <p class="text-h6">{{ label }}</p>
    <q-option-group
      v-model="form.races"
      :options="racesList"
      color="green"
      type="checkbox"
      size="xs"
      inline
      @update:model-value="emits('update:modelValue', form.races)"
    />
  </div>
</template>

<script setup>
import { reactive, defineProps, defineEmits } from "vue";

const emits = defineEmits(["update:modelValue"]);

const props = defineProps({
  modelValue: {
    type: Array,
    default: () => [],
  },
  label: {
    type: String,
    default: "Races",
  },
  racesList: {
    type: Array,
    default: () => [],
  },
});

const form = reactive({
  races: props.modelValue,
});
</script>
