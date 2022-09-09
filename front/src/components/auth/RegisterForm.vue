<template>
  <div>
    <q-form @submit="onSubmit" class="form-login q-mx-auto">
      <h2>Inscription</h2>
      <q-input v-model="email" label="E-mail" />
      <q-input v-model="password" label="Mot de passe" type="password" />
      <q-input
        v-model="password_confirmation"
        label="Confirmation du mot de passe"
        type="password"
      />
      <div class="text-center">
        <q-btn label="S'inscrire" type="submit" color="primary" />
      </div>
    </q-form>
  </div>
</template>

<script>
import axios from "axios";
import { ref } from "vue";
export default {
  setup() {
    const email = ref(null);
    const password = ref(null);
    const password_confirmation = ref(null);

    return {
      email,
      password,
      password_confirmation,
      onSubmit() {
        axios
          .post("http://localhost:8000/api/register", {
            email: email.value,
            password: password.value,
            password_confirmation: password_confirmation.value,
          })
          .then((res) => {
            console.log(res);
          });
      },
    };
  },
};
</script>
