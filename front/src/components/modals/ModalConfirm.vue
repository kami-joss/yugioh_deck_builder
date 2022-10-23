<template>
  <q-dialog v-model="state" persistent>
    <q-card>
      <q-card-section class="row items-center">
        <q-avatar :icon="icon" color="primary" text-color="white" />
        <span class="q-ml-sm"> {{ text }}.</span>
      </q-card-section>

      <q-card-actions align="right">
        <q-btn
          flat
          label="Cancel"
          color="primary"
          v-close-popup
          @click="emits('cancel')"
        />
        <q-btn
          flat
          label="Ok"
          color="primary"
          v-close-popup
          @click="emits('confirm')"
        />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script setup>
import { defineEmits, defineProps, reactive, ref, watch } from "vue";

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false,
  },
  icon: {
    type: String,
    default: "warning",
  },
  text: {
    type: String,
    default: "Are you sure ?",
  },
});

const state = ref(props.modelValue);

const emits = defineEmits(["save"]);

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
</script>
