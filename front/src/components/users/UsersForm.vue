<template>
  <q-card>
    <p class="text-h6">
      {{ user.id ? "Account " + user.name : "Create User" }}
    </p>
    <q-form @submit="onSubmit">
      <q-input
        v-model="form.name"
        label="Name"
        filled
        :rules="[(val) => !!val || 'Name is required']"
      />
      <q-input
        v-model="form.email"
        label="Email"
        filled
        :rules="[(val) => !!val || 'Email is required']"
      />

      <p class="text-h6">Password</p>
      <p>Don't enter anything if you don't want change your password</p>
      <q-input
        v-model="form.old_password"
        label="Old password"
        filled
        type="password"
      />
      <q-input v-model="form.password" label="New password" filled />
      <q-input
        v-model="form.password_confirmation"
        label="New password confirmation"
        filled
      />
    </q-form>

    <p class="text-negative cursor-pointer" @click="modalDelete = true">
      Close my account
    </p>

    <div class="row justify-center items-center">
      <q-btn color="primary" label="Save" type="submit" />
    </div>

    <modal-confirm
      v-model="modalDelete"
      text="Warn ! You will delete your account. This action is not reversible."
      @confirm="deleteUser"
    >
      <p class="bold">Input your password for continue</p>
      <q-input
        v-model="password_delete"
        label="Password"
        filled
        type="password"
      />
    </modal-confirm>
  </q-card>
</template>

<script setup>
import { reactive, ref, watch, defineEmits } from "vue";
import { api } from "boot/axios";
import { useQuasar } from "quasar";
import { pickBy } from "lodash";
import { useUserStore } from "src/stores/user";
import ModalConfirm from "src/components/modals/ModalConfirm.vue";
import { useRouter } from "vue-router";

const $q = useQuasar();
const router = useRouter();

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
});

const form = reactive({
  name: props.user.name,
  email: props.user.email,
  old_password: "",
  password: "",
  password_confirmation: "",
});

const emits = defineEmits(["update:password"]);

const userStore = useUserStore();

const onSubmit = () => {
  const formFormatted = pickBy(form, (val) => val != "");

  api.put(`/users/${props.user.id}`, formFormatted).then((response) => {
    $q.notify({
      message: "Your profile has been updated.",
      color: "positive",
      position: "top",
      icon: "check",
    });

    userStore.setUser(response.data.user);

    if (response.data.password) {
      emits("update:password");
    }
  });
};

const password_delete = ref("");
const modalDelete = ref(false);
const deleteUser = () => {
  api
    .delete(`/users/${props.user.id}`, {
      data: {
        password: password_delete.value,
      },
    })
    .then(() => {
      $q.notify({
        message: "Your account has been closed.",
        color: "positive",
        position: "top",
        icon: "check",
      });
      userStore.clearUser();
      router.replace("/");
    });
};
</script>
