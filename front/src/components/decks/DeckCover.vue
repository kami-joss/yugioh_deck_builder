<template>
  <q-card class="my-card" clickable tag="a" :href="`#/decks/${deck.id}`">
    <q-item>
      <q-item-section>
        <q-item-label> {{ deck.name }} </q-item-label>
        <q-item-label caption> By {{ deck.user?.name }} </q-item-label>
      </q-item-section>
    </q-item>

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
