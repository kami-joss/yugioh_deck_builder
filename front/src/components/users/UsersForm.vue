<template>
  <q-card>
    <p class="text-h6">
      {{ user.id ? "Account " + user.name : "Create User" }}
    </p>
  </q-card>
</template>

<script setup>
import { defineComponent, ref, watch } from "vue";

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
});
</script>
