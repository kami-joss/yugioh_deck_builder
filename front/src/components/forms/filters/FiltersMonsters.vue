<template>
  <div>
    <TypesFilter v-model="form.types" class="q-mb-md" />

    <AttributesFilter v-model="form.attributes" class="q-mb-md" />

    <RacesFilter
      v-model="form.races"
      :racesList="monsterRaces"
      class="q-mb-md"
    />

    <LevelsFilter v-model:levels="form.levels" class="q-mb-md" />

    <StatFilter
      v-model:max="form.atk.max"
      v-model:min="form.atk.min"
      label="ATK"
      class="q-mb-md"
    />

    <StatFilter
      v-model:max="form.def.max"
      v-model:min="form.def.min"
      label="DEF"
      class="q-mb-md"
    />
  </div>
</template>

<script setup>
import { reactive, defineEmits, watch, defineProps } from "vue";
import { useRoute } from "vue-router";
import StatFilter from "./StatFilter.vue";
import LevelsFilter from "./LevelsFilter.vue";
import RacesFilter from "./RacesFilter.vue";
import AttributesFilter from "./AttributesFilter.vue";
import TypesFilter from "./TypesFilter.vue";
import { monsterRaces } from "src/utils/cardUtils";

const route = useRoute();

const props = defineProps({
  modelValue: {
    type: Object,
    default: () => ({
      types: [],
      attributes: [],
      races: [],
      levels: { min: 0, max: 12 },
      atk: { min: 0, max: 0 },
      def: { min: 0, max: 0 },
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
