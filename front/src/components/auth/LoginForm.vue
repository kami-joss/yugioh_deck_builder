<template>
  <q-form @submit="login" @reset="onReset" class="form-login q-mx-auto">
    <h2>Connexion</h2>
    <q-input
      v-model="email"
      label="E-mail"
      :error="errors?.email ? true : false"
      :error-message="errors?.email ? errors.email[0] : ''"
    />
    <q-input
      v-model="password"
      label="Mot de passe"
      type="password"
      :error="errors?.password ? true : false"
      :error-message="errors?.password ? errors.password[0] : ''"
    />
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

    const errors = ref(null);

    const login = async () => {
      api
        .post("/sanctum/token", {
          email: email.value,
          password: password.value,
        })
        .then((response) => {
          localStorage.setItem("user", JSON.stringify(response.data.user));
          localStorage.setItem("token", response.data.token);

          userStore.setUser(response.data.user);
          userStore.token = response.data.token;

          api.defaults.headers.common[
            "Authorization"
          ] = `Bearer ${response.data.token}`;

          window.location.reload();
        })
        .catch((error) => {
          errors.value = error.response.data.errors;
        });
    };

    return {
      email,
      password,
      router,
      errors,
      login,
    };
  },
};
</script>
