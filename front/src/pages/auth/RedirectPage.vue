<template>
  <div class="container">
    <p>You must be logged in to watch this page.</p>
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
</template>

<script setup>
import { useRouter } from "vue-router";
const router = useRouter();
</script>
