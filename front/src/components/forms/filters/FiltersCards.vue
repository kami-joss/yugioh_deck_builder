<template>
  <q-card class="q-px-md q-py-md filters-container">
    <q-input
      v-model="monstersForm.name"
      rounded
      outlined
      :bg-color="'white'"
      :debounce="1000"
    >
      <template v-slot:append>
        <q-btn flat rounded icon="search" @click="onSearch" />
      </template>
    </q-input>

    <div class="q-pa-lg">
      <div>
        <q-checkbox
          v-model="cardTypes.monster.value"
          :label="cardTypes.monster.label"
        />
        <q-slide-transition>
          <div
            v-show="cardTypes.monster.value"
            :showing="cardTypes.monster.value"
          >
            <filters-monsters v-model="monstersForm" />
          </div>
        </q-slide-transition>
      </div>

      <q-separator />

      <div>
        <q-checkbox
          v-model="cardTypes.spell.value"
          :label="cardTypes.spell.label"
        />
        <q-slide-transition>
          <div v-show="cardTypes.spell.value">
            <filters-spells v-model="spellsForm" />
          </div>
        </q-slide-transition>
      </div>

      <q-separator />

      <div>
        <q-checkbox
          v-model="cardTypes.trap.value"
          :label="cardTypes.trap.label"
        />
        <q-slide-transition>
          <div v-show="cardTypes.trap.value">
            <filters-traps v-model="trapsForm" />
          </div>
        </q-slide-transition>
      </div>
    </div>

    <div class="row justify-center">
      <q-btn
        unelevated
        rounded
        color="primary"
        label="Search"
        icon-right="search"
        @click="onSearch"
      />
    </div>
  </q-card>
</template>

<script setup>
import { reactive, watch, defineEmits, ref } from "vue";
import { useRoute } from "vue-router";
import FiltersMonsters from "./FiltersMonsters.vue";
import FiltersSpells from "./FiltersSpells.vue";
import FiltersTraps from "./FiltersTraps.vue";
import { spellRaces, trapRaces } from "../../../utils/cardUtils";

const route = useRoute();
const emits = defineEmits(["search"]);

const cardTypes = reactive({
  monster: {
    label: "Monster",
    value: false,
  },
  spell: {
    label: "Spell",
    value: false,
  },
  trap: {
    label: "Trap",
    value: false,
  },
});

const onSearch = () => {
  const form = {
    ...monstersForm.value,
    ...spellsForm.value,
    ...trapsForm.value,
    races: monstersForm.value.races.concat(
      spellsForm.value.races,
      trapsForm.value.races
    ),
  };

  emits("search", form);
};

const monstersForm = ref({
  name: "",
  types: route.query.types ?? [],
  attributes: route.query.attributes ?? [],
  races: route.query.races ?? [],
  levels: route.query.levels ?? { min: 0, max: 12 },
  atk: route.query.atk ?? { min: 0, max: 0 },
  def: route.query.def ?? { min: 0, max: 0 },
});

const spellsForm = ref({
  races: route.query.races ?? [],
});

const trapsForm = ref({
  races: route.query.races ?? [],
});

watch(
  () => monstersForm.value.name,
  () => {
    onSearch();
  }
);
</script>

<style scoped lang="scss">
.filters-container {
  max-height: 90vh;
  overflow: auto;
}
</style>
