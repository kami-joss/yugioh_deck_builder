<template>
  <div>
    <q-input
      v-model="form.name"
      rounded
      outlined
      :bg-color="'white'"
      :debounce="450"
    >
      <template v-slot:append>
        <q-btn flat rounded icon="search" />
      </template>
    </q-input>

    <div class="q-pa-lg">
      <div>
        <q-checkbox
          v-model="cardTypes.monster.value"
          :label="cardTypes.monster.label"
        />
        <q-option-group
          v-if="cardTypes.monster.value"
          v-model="form.types.monster"
          :options="monsterTypes"
          color="green"
          type="checkbox"
          size="xs"
          inline
        />
      </div>
      <div>
        <q-checkbox
          v-model="cardTypes.spell.value"
          :label="cardTypes.spell.label"
        />
        <q-option-group
          v-if="cardTypes.spell.value"
          v-model="form.types.spell"
          :options="spellTypes"
          color="green"
          type="checkbox"
          size="xs"
        />
      </div>
      <div>
        <q-checkbox
          v-model="cardTypes.trap.value"
          :label="cardTypes.trap.label"
        />
        <q-option-group
          v-if="cardTypes.trap.value"
          v-model="form.types.trap"
          :options="trapTypes"
          color="green"
          type="checkbox"
          size="xs"
          inline
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, watch, defineEmits } from "vue";

const cardTypes = reactive({
  monster: {
    label: "Monster",
    value: true,
  },
  spell: {
    label: "Spell",
    value: true,
  },
  trap: {
    label: "Trap",
    value: true,
  },
});

const monsterTypes = [
  {
    label: "Normal",
    value: "normal monster",
  },
  {
    label: "Effect",
    value: "effect",
  },
  {
    label: "Fusion",
    value: "fusion",
  },
  {
    label: "Ritual",
    value: "ritual",
  },
  {
    label: "Synchro",
    value: "synchro",
  },
  {
    label: "Xyz",
    value: "xyz",
  },
  {
    label: "Pendulum",
    value: "pendulum",
  },
  {
    label: "Link",
    value: "link",
  },
];

const spellTypes = [
  {
    label: "Normal",
    value: "normal spell",
  },
  {
    label: "Continuous",
    value: "continuous",
  },
  {
    label: "Equip",
    value: "equip",
  },
  {
    label: "Field",
    value: "field",
  },
  {
    label: "Quick-Play",
    value: "quick-play",
  },
  {
    label: "Ritual",
    value: "ritual spell",
  },
];

const trapTypes = [
  {
    label: "Normal",
    value: "normal trap",
  },
  {
    label: "Continuous",
    value: "continuous",
  },
  {
    label: "Counter",
    value: "counter",
  },
];

const form = reactive({
  name: "",
  types: {
    monster: [],
    spell: [],
    trap: [],
  },
});
const emits = defineEmits(["search"]);

watch(
  () => form.name,
  (val) => {
    emits("search", form);
  }
);

watch(cardTypes, (val) => {
  if (val.monster.value) {
    if (!form.types.monster.length) {
      form.types.monster = monsterTypes.map((type) => type.value);
    }
  } else {
    form.types.monster = [];
  }

  if (val.spell.value) {
    if (!form.types.spell.length) {
      form.types.spell = spellTypes.map((type) => type.value);
    }
  } else {
    form.types.spell = [];
  }

  if (val.trap.value) {
    if (!form.types.trap.length) {
      form.types.trap = trapTypes.map((type) => type.value);
    }
  } else {
    form.types.trap = [];
  }
});
</script>
