<template>
  <div>
    <div class="row">
      <q-input
        v-model="form.search"
        filled
        dense
        placeholder="Search deck"
        icon="search"
        :debounce="1000"
      >
        <template v-slot:append>
          <q-btn round dense flat icon="search" @click="onClick" />
        </template>
      </q-input>
      <q-select
        v-model="form.searchBy"
        outlined
        dense
        :options="options"
        label="Search by"
        class="select-searchBy"
      />
      <q-checkbox v-model="form.illegal" label="Include forbidden decks" />
    </div>
  </div>
</template>

<script setup>
import { defineProps, reactive, watch } from "vue";
const options = ["Name", "Author"];

const props = defineProps({
  modelValue: {
    type: Object,
    required: true,
  },
});

const form = reactive(props.modelValue);

const emits = defineEmits(["input", "click", "search"]);
const onClick = () => {
  emits("input", form);
};
watch(
  () => form,
  (val) => {
    emits("input", val);
  },
  { deep: true }
);
</script>

<style scoped>
.select-searchBy {
  min-width: 150px;
}
</style>
