<template>
  <q-card bordered>
    <q-card-section horizontal>
      <q-card-section class="q-mr-xl">
        <p class="text-h6">{{ deck.name }}</p>
        <p class="text-caption">By {{ deck.user?.name }}</p>
        <p>Main: {{ deck.main_deck?.length }} cards</p>
        <p>Extra: {{ deck.extra_deck?.length }} cards</p>
        <p>Side: {{ deck.side_deck?.length }} cards</p>
        <q-chip
          v-if="deck.illegal"
          color="negative"
          text-color="white"
          label="Forbidden"
        />
      </q-card-section>
      <q-img
        :src="deck.image_path"
        style="max-width: 200px"
        class="image_cover"
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
