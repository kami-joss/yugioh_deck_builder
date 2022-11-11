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
      <div class="row justify-center items-center">
        <q-btn color="primary" label="Save" type="submit" />
      </div>
    </q-form>
  </q-card>
</template>

<script setup>
import { reactive, ref, watch, defineEmits } from "vue";
import { api } from "boot/axios";
import { useQuasar } from "quasar";
import { pickBy } from "lodash";
import { useUserStore } from "src/stores/user";

const $q = useQuasar();

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
</script>
