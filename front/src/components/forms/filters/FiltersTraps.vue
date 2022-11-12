<template>
  <div>
    <RacesFilter v-model="form.trap" :racesList="trapRaces" :label="'Types'" />
  </div>
</template>

<script setup>
import { defineProps, reactive, watch } from "vue";
import RacesFilter from "./RacesFilter.vue";
import { trapRaces } from "src/utils/cardUtils";

const props = defineProps({
  modelValue: {
    type: Object,
    default: () => ({
      races: [],
    }),
  },
});

const form = reactive({
  ...props.modelValue,
});

const emits = defineEmits(["update:modelValue"]);

watch(form, () => {
  emits("update:modelValue", form);
});
</script>
