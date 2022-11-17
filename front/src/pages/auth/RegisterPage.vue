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
      <register-form />
      <div class="row justify-center items-center q-ma-md">
        <p>
          You have an account ?
          <q-btn label="Login" @click="router.push('/login')" />
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import registerForm from "components/auth/RegisterForm.vue";
import { useRouter } from "vue-router";
export default {
  components: {
    registerForm,
  },
  setup() {
    const router = useRouter();
    return {
      router,
    };
  },
};
</script>
