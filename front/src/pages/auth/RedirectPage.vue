<template>
  <div class="page-container">
    <p class="text-h6">You must be logged in to watch this page.</p>
    <div class="row justify-center gap-1">
      <q-btn
        label="Back to home"
        color="primary"
        icon="home"
        @click="() => router.push('/')"
      />
      <q-btn
        label="Login"
        color="primary"
        icon="login"
        @click="() => router.push('/login')"
      />
    </div>
  </div>
</template>

<script setup>
import { useRouter } from "vue-router";
const router = useRouter();
</script>

<style scope lang="scss">
.page-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 20px;
  height: 50vh;
}
</style>
