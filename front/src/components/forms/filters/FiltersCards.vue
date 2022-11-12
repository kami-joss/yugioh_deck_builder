<template>
  <q-card class="q-px-md q-py-md filters-container">
    <q-input v-model="search" outlined dense filled :debounce="1000">
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
import { pickBy } from "lodash";

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
    races: monstersForm.value.races,
    trap: trapsForm.value.trap,
    spell: spellsForm.value.spell,
    name: search.value,
  };

  const formFormatted = pickBy(
    form,
    (value) => value !== "" && value !== null && value.length > 0
  );

  emits("search", formFormatted);
};

const search = ref(null);

const monstersForm = ref({
  types: route.query.types ?? [],
  attributes: route.query.attributes ?? [],
  races: route.query.races ?? [],
  atkMax: route.query.atkMax ?? null,
  atkMin: route.query.atkMin ?? null,
  defMax: route.query.defMax ?? null,
  defMin: route.query.defMin ?? null,
  levelMax: route.query.levelMax ?? null,
  levelMin: route.query.levelMin ?? null,
});

const spellsForm = ref({
  spell: route.query.spell ?? [],
});

const trapsForm = ref({
  trap: route.query.trap ?? [],
});

watch(
  () => search.value,
  () => {
    onSearch();
  }
);
</script>

<style scoped lang="scss">
.filters-container {
  max-height: 90vh;
  height: max-content;
  overflow: auto;
}
</style>
