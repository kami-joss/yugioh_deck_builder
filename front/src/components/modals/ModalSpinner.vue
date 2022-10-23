<template>
  <q-dialog v-model="state" persistent>
    <q-spinner-hourglass color="amber-5" size="7rem" thickness="10" />
  </q-dialog>
</template>

<script setup>
import { defineEmits, defineProps, reactive, ref, watch } from "vue";

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false,
  },
});

const state = ref(props.modelValue);

watch(
  () => props.modelValue,
  (val) => {
    state.value = props.modelValue;
  }
);

watch(
  () => state.value,
  (val) => {
    emits("update:modelValue", val);
  }
);

const emits = defineEmits(["update:modelValue"]);
</script>
