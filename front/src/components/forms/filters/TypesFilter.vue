<template>
  <div>
    <p class="text-h6">{{ label }}</p>
    <q-option-group
      v-model="form.types"
      :options="monsterTypes"
      color="green"
      type="checkbox"
      size="xs"
      inline
      @update:model-value="emits('update:modelValue', form.types)"
    />
  </div>
</template>

<script setup>
import { reactive, defineProps, defineEmits } from "vue";
import { monsterTypes } from "../../../utils/cardUtils";

const emits = defineEmits(["update:modelValue"]);

const props = defineProps({
  modelValue: {
    type: Array,
    default: () => [],
  },
  label: {
    type: String,
    default: "Types",
  },
});

const form = reactive({
  types: props.modelValue,
});
</script>
