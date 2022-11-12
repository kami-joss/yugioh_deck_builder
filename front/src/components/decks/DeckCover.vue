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
    <div class="image_container">
      <q-img
        :src="deck.image_path"
        spinner-color="black"
        style="width: 100%; position: relative"
      />
    </div>
  </q-card>
</template>

<script setup>
import { defineProps } from "vue";
import { useUserStore } from "src/stores/user";

const userStore = useUserStore();

const props = defineProps({
  deck: {
    type: Object,
    required: true,
  },
});
</script>

<style scoped lang="scss">
.image_container {
  max-height: 200px;
  overflow: hidden;
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>
