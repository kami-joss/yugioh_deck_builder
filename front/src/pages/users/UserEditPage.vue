<template>
  <div>
    <users-form :user="user" />
  </div>
</template>

<script setup>
import { defineComponent, onMounted, ref, watch } from "vue";
import UsersForm from "components/users/UsersForm.vue";
import { useUserStore } from "src/stores/user";
import { api } from "boot/axios";

const userStore = useUserStore();
const user = ref(userStore.getUser);

onMounted(() => {
  api.get(`user/${userStore.getUser.id}/edit`).then((response) => {
    console.log(response.data);
  });
});
</script>
