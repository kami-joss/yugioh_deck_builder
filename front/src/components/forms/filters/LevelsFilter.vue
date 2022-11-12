<template>
  <div>
    <p class="text-h6">{{ label }}</p>
    <div class="row justify-between">
      <q-input
        v-model="form.levelMin"
        type="number"
        outlined
        @update:model-value="emits('update:levelMin', form.levelMin)"
      />
      <q-input
        v-model="form.levelMax"
        type="number"
        outlined
        @update:model-value="emits('update:levelMax', form.levelMax)"
      />
    </div>
    <!-- <q-range
      v-model="form.levels"
      marker-labels
      :min="0"
      :max="12"
      @change="handleRange"
    /> -->
  </div>
</template>

<script setup>
import { reactive, defineProps, defineEmits, watch } from "vue";

const emits = defineEmits(["update:levelMax", "update:levelMin"]);

const props = defineProps({
  levelMax: {
    type: Number,
    default: () => 12,
  },
  levelMin: {
    type: Number,
    default: () => 0,
  },
  label: {
    type: String,
    default: "Levels",
  },
});

const form = reactive({
  levelMax: props.levelMax,
  levelMin: props.levelMin,
});

// watch(form, () => {
//   emits("update:levels", form);
// });

const handleRange = (value) => {
  // emits("update:levels", {});
};
</script>
