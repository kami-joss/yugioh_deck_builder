<template>
  <div class="row gap-1 justify-center">
    <deck-cover
      v-for="deck in decks"
      :deck="deck"
      :key="deck.id"
      class="col-sm-5 col-md-2 col-lg-1"
    />
  </div>
</template>

<script setup>
import { onMounted, reactive, ref, watch, defineProps } from "vue";
import DeckCover from "src/components/decks/DeckCover";

const props = defineProps({
  decks: {
    type: Array,
    required: true,
  },
});
</script>
