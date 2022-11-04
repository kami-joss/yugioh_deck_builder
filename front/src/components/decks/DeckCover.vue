<template>
  <q-card class="my-card" clickable tag="a" :href="`#/decks/${deck.id}`">
    <q-card-section>
      <q-item>
        <q-item-section>
          <q-item-label> {{ deck.name }} </q-item-label>
          <q-item-label caption> By {{ deck.user?.name }} </q-item-label>
        </q-item-section>
      </q-item>
      <q-chip
        v-if="deck.illegal"
        color="negative"
        text-color="white"
        label="Forbidden"
      />
    </q-card-section>
    <img src="https://cdn.quasar.dev/img/parallax2.jpg" />
  </q-card>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
  deck: {
    type: Object,
    required: true,
  },
});
</script>
