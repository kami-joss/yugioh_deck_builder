<template>
  <div>
    <TypesFilter v-model="form.types" class="q-mb-md" />

    <AttributesFilter v-model="form.attributes" class="q-mb-md" />

    <RacesFilter
      v-model="form.races"
      :racesList="monsterRaces"
      class="q-mb-md"
    />

    <LevelsFilter
      v-model:levelMax="form.levelMax"
      v-model:levelMin="form.levelMin"
      class="q-mb-md"
    />

    <StatFilter
      v-model:max="form.atkMax"
      v-model:min="form.atkMin"
      label="ATK"
      class="q-mb-md"
    />

    <StatFilter
      v-model:max="form.defMax"
      v-model:min="form.defMin"
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

import { pickBy } from "lodash";

const route = useRoute();

const props = defineProps({
  modelValue: {
    type: Object,
    default: null,
  },
});

const form = reactive({
  ...props.modelValue,
});

const emits = defineEmits(["update:modelValue"]);

watch(form, () => {
  const formFormatted = pickBy(form, (value) => value !== "" && value !== null);
  emits("update:modelValue", formFormatted);
});
</script>
