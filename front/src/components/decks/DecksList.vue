<template>
  <div class="row gap-1 deck-cover-container">
    <deck-cover
      v-for="deck in decks"
      :deck="deck"
      :key="deck.id"
      :buttons="deck.user?.id == userStore.getUser?.id ? ['delete'] : []"
      class="col-xs-5 col-sm-3 col-md-2 col-lg-2"
    />
  </div>
</template>

<script setup>
import { onMounted, reactive, ref, watch, defineProps } from "vue";
import { useUserStore } from "src/stores/user";
import DeckCover from "src/components/decks/DeckCover";

const props = defineProps({
  decks: {
    type: Array,
    required: true,
  },
});

const userStore = useUserStore();
</script>

<style scoped lang="scss">
.deck-cover-container {
  @media screen and (max-width: 600px) {
    justify-content: center;
  }
}
</style>
