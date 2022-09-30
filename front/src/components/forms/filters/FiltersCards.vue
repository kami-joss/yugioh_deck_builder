<template>
  <q-card class="q-px-md q-py-md">
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
        <div v-if="cardTypes.monster.value" :showing="cardTypes.monster.value">
          <filters-monsters v-model="monstersForm" />
        </div>
      </div>

      <q-separator />

      <div>
        <q-checkbox
          v-model="cardTypes.spell.value"
          :label="cardTypes.spell.label"
        />
        <div v-if="cardTypes.spell.value">
          <filters-spells v-model="spellsForm" />
        </div>
      </div>

      <q-separator />

      <div>
        <q-checkbox
          v-model="cardTypes.trap.value"
          :label="cardTypes.trap.label"
        />
        <div v-if="cardTypes.trap.value">
          <filters-traps v-model="trapsForm" />
        </div>
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
    ...monstersForm,
    ...spellsForm,
    ...trapsForm,
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

// watch(
//   () => cardTypes.monster.value,
//   (val) => {
//     let monsterTypesVal = monsterTypes.map((type) => type.value);
//     if (!val) {
//       form.value.types = form.value.types.filter(
//         (type) => !monsterTypesVal.includes(type)
//       );
//     } else {
//       form.value.types = form.value.types.concat(monsterTypesVal);
//     }
//   }
// );

// watch(
//   () => cardTypes.spell.value,
//   (val) => {
//     let spellRacesVal = spellRaces.map((type) => type.value);
//     if (!val) {
//       form.value.types = form.value.types.filter(
//         (type) => !spellRacesVal.includes(type)
//       );
//     } else {
//       form.value.types = form.value.types.concat(spellRacesVal);
//     }
//   }
// );

// watch(
//   () => cardTypes.trap.value,
//   (val) => {
//     let trapRacesVal = trapRaces.map((type) => type.value);
//     if (!val) {
//       form.value.types = form.value.types.filter(
//         (type) => !trapRacesVal.includes(type)
//       );
//     } else {
//       form.value.types = form.value.types.concat(trapRacesVal);
//     }
//   }
// );
</script>
