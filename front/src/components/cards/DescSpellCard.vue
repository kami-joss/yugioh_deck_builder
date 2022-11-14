<template>
  <div>
    <q-card class="card-desc" bordered>
      <q-card-section horizontal class="card-desc_section">
        <div class="text-h6">{{ card.name }}</div>
      </q-card-section>

      <q-separator />

      <q-card-section horizontal class="row">
        <q-card-section class="col text-center">{{ card.type }}</q-card-section>
        <q-separator vertical />
        <q-card-section class="col text-center">
          <div>{{ card.race }}</div>
        </q-card-section>
      </q-card-section>

      <q-separator />

      <q-card-section horizontal class="card-desc_section">
        <p>{{ card.desc }}</p>
      </q-card-section>
    </q-card>
  </div>
</template>

<script setup>
const props = defineProps(["card"]);
</script>
