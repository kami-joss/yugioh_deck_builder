<template>
  <q-form @submit="onSubmit" @reset="onReset" class="form-login q-mx-auto">
    <h2>Connexion</h2>
    <q-input v-model="email" label="E-mail" />
    <q-input v-model="password" label="Mot de passe" type="password" />
    <div class="text-center">
      <q-btn label="Se connecter" type="submit" color="primary" />
    </div>
  </q-form>
</template>

<script>
import { ref } from "vue";
import { api } from "boot/axios";
import { useUserStore } from "src/stores/user";
import { useRoute, useRouter } from "vue-router";

export default {
  setup() {
    const userStore = useUserStore();

    const route = useRoute();
    const router = useRouter();

    const email = ref(null);
    const password = ref(null);

    return {
      email,
      password,
      router,
      onSubmit() {
        userStore.login(email.value, password.value).then(() => {
          router.back();
        });
      },
    };
  },
};
</script>
