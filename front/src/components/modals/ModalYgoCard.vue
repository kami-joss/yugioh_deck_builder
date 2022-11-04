<template>
  <q-dialog v-model="state">
    <q-btn
      round
      size="md"
      color="red-5"
      icon="close"
      class="lt-md btn-filter-close"
      @click="emits('update:modelValue', state)"
    />
    <full-ygo-card v-if="card" :card="card" :key="card.id" class="z-10">
      <template #before-desc>
        <div class="row">
          <q-input
            v-model.number="form.quantity"
            type="number"
            filled
            class="col bg-white"
          />
          <q-btn
            label="Ajouter au deck"
            icon="add"
            size="md"
            color="primary"
            @click="emits('add:card', { card, quantity: form.quantity })"
          />
        </div>
      </template>
    </full-ygo-card>
  </q-dialog>
</template>

<script setup>
import { defineEmits, defineProps, reactive, ref, watch } from "vue";
import FullYgoCard from "src/components/cards/FullYgoCard.vue";

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false,
  },
  card: {
    type: Object,
    default: null,
  },
});

const state = ref(props.modelValue);
const form = reactive({
  quantity: 1,
});

watch(
  () => props.modelValue,
  (val) => {
    state.value = props.modelValue;
  }
);

watch(
  () => state.value,
  (value) => {
    emits("update:modelValue", value);
  }
);

const emits = defineEmits(["update:modelValue", "add:card"]);
</script>

<style scoped lang="scss">
.test {
  background-color: azure;
}
</style>
