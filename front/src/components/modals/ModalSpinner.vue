<template>
  <q-dialog v-model="state" persistent>
    <span class="column items-center"> </span>

    <q-card v-if="text" class="column items-center q-pa-md">
      <p class="text-h6 text-center">{{ text }}</p>
      <q-spinner-hourglass color="amber-5" size="7rem" thickness="10" />
    </q-card>

    <q-spinner-hourglass v-else color="amber-5" size="7rem" thickness="10" />
  </q-dialog>
</template>

<script setup>
import { defineEmits, defineProps, reactive, ref, watch } from "vue";

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false,
  },
  text: {
    type: String,
    default: null,
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
