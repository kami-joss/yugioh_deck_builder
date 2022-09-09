<template>
  <div>
    <q-card class="card-desc">
      <q-card-section dark horizontal class="card-desc_section">
        <div class="text-h6">{{ card.name }}</div>
      </q-card-section>

      <q-separator />

      <q-card-section dark horizontal class="row items-center">
        <q-card-section>{{ card.type }}</q-card-section>
        <q-separator vertical spaced />
        <q-card-section> level {{ card.level }} </q-card-section>
        <q-separator vertical spaced />
        <q-card-section>
          {{ card.attribute }}
        </q-card-section>
      </q-card-section>

      <q-separator />

      <q-card-section horizontal class="card-desc_section">
        <div>{{ card.race }}</div>
      </q-card-section>

      <q-separator />

      <q-card-section horizontal class="card-desc_section">
        <p>{{ card.desc }}</p>
      </q-card-section>

      <q-separator />

      <q-card-section horizontal class="row justify-center">
        <q-card-section>ATK / {{ card.atk }}</q-card-section>
        <q-separator vertical spaced />
        <q-card-section>DEF / {{ card.def }}</q-card-section>
      </q-card-section>
    </q-card>
  </div>
</template>

<script setup>
const props = defineProps(["card"]);
</script>
