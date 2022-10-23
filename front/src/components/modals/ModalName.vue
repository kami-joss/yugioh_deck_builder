<template>
  <div class="container">
    <q-dialog v-model="state" class="modal">
      <q-card class="modal-container">
        <q-btn
          round
          size="md"
          color="red-5"
          icon="close"
          class="lt-md btn-filter-close"
        />
        <q-input
          v-model="form.name"
          class="input-text"
          style="width: 400px"
          placeholder="Enter a name"
        />
        <q-btn
          label="Save"
          icon="save"
          size="md"
          color="primary"
          @click="emits('save', form.name)"
        />
      </q-card>
    </q-dialog>
  </div>
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

const form = reactive({
  name: "",
});

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

<style scoped>
.modal-container {
  padding: 20px;
  gap: 20px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
</style>
