<template>
  <div>
    <p class="text-h6">{{ label }}</p>
    <q-option-group
      v-model="form.attributes"
      :options="monsterAttributes"
      color="green"
      type="checkbox"
      size="xs"
      inline
      @update:model-value="emits('update:modelValue', form.attributes)"
    />
  </div>
</template>

<script setup>
import { reactive, defineProps, defineEmits } from "vue";
import { monsterAttributes } from "../../../utils/cardUtils";

const emits = defineEmits(["update:modelValue"]);

const props = defineProps({
  modelValue: {
    type: Array,
    default: () => [],
  },
  label: {
    type: String,
    default: "Attributes",
  },
});

const form = reactive({
  attributes: props.modelValue,
});
</script>
