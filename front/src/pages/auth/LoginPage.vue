<template>
  <div>
    <q-btn
      label="Back home"
      icon="arrow_back"
      class="q-ma-md"
      flat
      @click="router.push('/')"
    />
    <div class="container">
      <login-form />
      <div class="row justify-center items-center q-ma-md">
        <p>
          Not member yet ?
          <q-btn label="Register" @click="router.push('/register')" />
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import LoginForm from "components/auth/LoginForm.vue";
import { useRouter } from "vue-router";

export default {
  components: {
    LoginForm,
  },
  setup() {
    const router = useRouter();
    return {
      router,
    };
  },
};
</script>
