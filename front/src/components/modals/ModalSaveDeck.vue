<template>
  <div class="container">
    <q-dialog v-model="state" class="modal">
      <q-card class="modal-container" style="max-width: 400px; width: 100%">
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
          style="width: 100%"
          lazy-rules
          :rules="[(val) => !!val || 'Name is required']"
        />
        <q-input
          v-model="form.description"
          filled
          style="width: 100%"
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
  name: {
    type: String,
    default: "",
  },
  isPublic: {
    type: Boolean,
    default: true,
  },
  description: {
    type: String,
    default: "",
  },
});

const state = ref(props.modelValue);

const form = reactive({
  name: props.name,
  description: props.description,
  isPublic: props.isPublic,
});

const nameRef = ref(null);

const onSave = () => {
  nameRef.value.validate();

  if (nameRef.value.hasError) return;

  emits("save", form);
  state.value = false;
};

const emits = defineEmits(["save", "update:modelValue"]);

watch(
  () => props,
  (val) => {
    state.value = props.modelValue;
    // form.name = props.name;
    // form.description = props.description;
    // form.isPublic = props.isPublic;
  },
  { deep: true }
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
