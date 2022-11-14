<template>
  <div class="container">
    <modal-spinner
      v-model="modal"
      text="You have changed your password. You go to be redirected to login page."
    />
    <users-form :user="user" @update:password="onChangePassword" />
  </div>
</template>

<script setup>
import { defineComponent, onMounted, ref, watch } from "vue";
import UsersForm from "components/users/UsersForm.vue";
import { useUserStore } from "src/stores/user";
import { api } from "boot/axios";
import ModalSpinner from "src/components/modals/ModalSpinner";
import { useRouter } from "vue-router";

const modal = ref(false);

const router = useRouter();

const userStore = useUserStore();
const user = ref(userStore.getUser);

const onChangePassword = () => {
  modal.value = true;

  window.setTimeout(() => {
    modal.value = false;
    userStore.logout();
    router.replace("/login");
  }, 3000);
};
</script>
