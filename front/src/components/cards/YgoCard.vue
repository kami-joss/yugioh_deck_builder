<template>
  <q-card class="ygo-card" @click="handleClick" @mouseover="handleHover">
    <div style="width: 100%; position: relative">
      <q-img :src="card.image_path" spinner-color="black" />
      <q-icon
        v-if="card.number_allowed == 0"
        name="block"
        color="negative"
        class="icon-forbidden"
        size="2rem"
      >
        <q-tooltip
          class="bg-red"
          anchor="bottom middle"
          self="top middle"
          :offset="[10, 10]"
        >
          <strong> Forbidden </strong>
        </q-tooltip>
      </q-icon>
      <div
        v-if="card.number_allowed == 2 || card.number_allowed == 1"
        class="icon-warning"
      >
        {{ card.number_allowed }}
        <q-tooltip
          class="bg-warning"
          anchor="bottom middle"
          self="top middle"
          :offset="[10, 10]"
        >
          <strong>
            {{ card.number_allowed == 1 ? "Limited" : "Semi-Limited" }}
          </strong>
        </q-tooltip>
      </div>
    </div>
    <div v-if="label">
      <q-separator spaced dark />
      <p class="text-center">
        {{ card.name }}
      </p>
    </div>

    <q-dialog v-if="!$q.platform.is.desktop" v-model="modalCard">
      <div class="">
        <q-btn
          icon="close"
          color="white"
          text-color="black"
          round
          v-close-popup
        />
      </div>
      <FullYgoCard :card="card">
        <!-- <template #before-desc>
          <q-input
            v-model="numberToAdd"
            outlined
            dense
            label="Number of cards"
            type="number"
            min="1"
            max="3"
            :rules="[
              (val) => (val > 0 && val <= 3) || 'Must be between 1 and 3',
            ]"
          />
          <q-btn
            color="primary"
            label="Add to deck"
            @click="
              emits('click:add:multiple', {
                card: props.card,
                quantity: numberToAdd,
              })
            "
          />
        </template> -->
      </FullYgoCard>
    </q-dialog>
  </q-card>
</template>

<script setup>
import { defineEmits, defineProps, ref, watch } from "vue";
import { useRouter } from "vue-router";
import { useQuasar } from "quasar";
import FullYgoCard from "./FullYgoCard.vue";

const router = useRouter();
const $q = useQuasar();
const numberToAdd = ref(1);

const props = defineProps({
  card: {
    type: Object,
    required: true,
  },
  label: {
    type: String,
    default: null,
  },
  clickable: {
    type: Boolean,
    default: false,
  },
  cardSelected: {
    type: Object,
    default: null,
  },
});

const emits = defineEmits([
  "click:show-card",
  "hover:card",
  "add",
  "click:add",
  "click:add:multiple",
]);
const options = ref(false);
const modalCard = ref(false);

const handleClick = () => {
  $q.platform.is.desktop
    ? emits("click:add", props.card)
    : (modalCard.value = true);
};

const handleHover = () => {
  emits("hover:card", props.card);
};

watch(
  () => props.cardSelected,
  (carte) => {
    carte.id == props.card.id
      ? (options.value = true)
      : (options.value = false);
  }
);
</script>

<style scoped lang="scss">
.ygo-card {
  cursor: pointer;
  max-height: 200px;
}
.icon-forbidden {
  position: absolute;
  top: 0;
  right: 0;
  font-size: 1.5rem;
}

.icon-warning {
  position: absolute;
  top: 0;
  right: 0;
  padding: 5px;
  color: white;
  font-weight: bold;
  border-radius: 500px;
  background-color: $amber-6;
}
</style>
