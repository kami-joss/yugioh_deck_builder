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

export default {
  setup() {
    const email = ref(null);
    const password = ref(null);

    return {
      email,
      password,
      onSubmit() {
        api
          .post("/sanctum/token", {
            email: email.value,
            password: password.value,
          })
          .then((response) => {
            localStorage.setItem("sanctum_token", response.data);
            axios.defaults.headers.common[
              "Authorization"
            ] = `Bearer ${response.data}`;
          })
          .catch((error) => {
            console.log(error);
          });
      },
    };
  },
};
</script>
