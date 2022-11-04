<template>
  <q-card>
    <q-card-section>
      <p class="text-h6">{{ deck.name }}</p>
      <p class="text-caption">By {{ deck.user?.name }}</p>
      <p>{{ deck.cards?.main?.length }} cards</p>
      <q-chip color="negative" text-color="white" label="Forbidden" />
    </q-card-section>
    <q-card-section>
      <q-img
        src="https://cdn.quasar.dev/img/parallax2.jpg"
        style="width: 100%; height: 200px"
      />
    </q-card-section>
    <q-card-section>
      <p>
        {{ deck.description }}
      </p>
    </q-card-section>
  </q-card>
</template>

<script setup>
import { defineProps, ref } from "vue";

const props = defineProps({
  deck: {
    type: Object,
    required: true,
  },
});
</script>
