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
          ref="nameRef"
          placeholder="Enter a name"
          class="input-text"
          style="width: 400px"
          lazy-rules
          :rules="[(val) => !!val || 'Name is required']"
        />
        <q-input
          v-model="form.description"
          filled
          style="width: 400px"
          type="textarea"
          placeholder="Enter a description"
        />
        <q-checkbox v-model="form.isPublic" label="Public" color="teal" />
        <div class="row gap-1">
          <q-btn
            label="Save"
            icon="save"
            size="md"
            color="primary"
            @click="onSave"
          />
        </div>
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
  description: "",
  isPublic: false,
});

const nameRef = ref(null);

const onSave = () => {
  nameRef.value.validate();

  if (nameRef.value.hasError) return;

  emits("save", form);
  state.value = false;
};

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
