<template>
  <div class="container">
    <login-form />
  </div>
</template>

<script>
import { defineComponent } from "vue";
import axios from "axios";
import LoginForm from "components/auth/LoginForm.vue";

export default {
  components: {
    LoginForm,
  },
  setup() {
    return {};
  },
};
</script>
