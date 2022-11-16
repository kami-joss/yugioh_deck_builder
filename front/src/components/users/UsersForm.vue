<template>
  <q-card class="column items-center q-pa-md">
    <p class="text-h6">
      {{ user.id ? "Account of " + user.name : "Create User" }}
    </p>
    <q-avatar class="q-mb-md" size="5rem" rounded>
      <img :src="user.image_path" />
    </q-avatar>
    <q-separator />
    <q-form @submit="onSubmit">
      <div>
        <p class="text-h6">Avatar</p>
        <q-uploader
          style="max-width: 300px"
          label="Pick an image"
          :factory="uploadAvatar"
          max-files="1"
          accept=".jpg, .jpeg, .png"
          @rejected="onRejected"
        />
      </div>
      <div>
        <p class="text-h6">Login</p>
        <q-input
          v-model="form.name"
          label="Name *"
          filled
          :rules="[
            (val) => !!val || 'Name is required',
            (val) => val.length <= 24 || 'Name must be less than 24 characters',
          ]"
          :error="errors?.name ? true : false"
          :error-message="errors?.name ? errors.name[0] : ''"
        />
      </div>
      <q-input
        v-model="form.email"
        label="Email *"
        filled
        :rules="[(val) => !!val || 'Email is required']"
        :error="errors?.email ? true : false"
        :error-message="errors?.email ? errors.email[0] : ''"
      />

      <p class="text-h6">Password</p>
      <p>Don't enter anything if you don't want change your password</p>
      <q-input
        v-model="form.old_password"
        label="Old password"
        filled
        type="password"
        :error="errors?.old_password ? true : false"
        :error-message="errors?.old_password ? errors.old_password[0] : ''"
      />
      <q-input
        v-model="form.password"
        label="New password"
        filled
        type="password"
        :error="errors?.password ? true : false"
        :error-message="errors?.password ? errors.password[0] : ''"
      />
      <q-input
        v-model="form.password_confirmation"
        label="New password confirmation"
        filled
        type="password"
        :error="errors?.password_confirmation ? true : false"
        :error-message="
          errors?.password_confirmation ? errors.password_confirmation[0] : ''
        "
      />
      <div class="row justify-center items-center">
        <q-btn color="primary" label="Save" type="submit" />
      </div>
    </q-form>

    <p class="text-negative cursor-pointer q-my-md" @click="modalDelete = true">
      Close my account
    </p>

    <modal-confirm
      v-model="modalDelete"
      text="Warn ! You will delete your account. This action is not reversible."
      @confirm="deleteUser"
    >
      <q-card>
        <q-card-section>
          <p class="bold">Input your password for continue</p>
          <q-input
            v-model="password_delete"
            label="Password"
            filled
            type="password"
            :error="errors?.password_delete ? true : false"
            :error-message="errors?.password_deleted ? errors.delete[0] : ''"
            :rules="[(val) => !!val || 'Password is required']"
          />
        </q-card-section>
      </q-card>
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

const uploadAvatar = (files) => {
  const file_path = files[0];
  const fileData = new FormData();

  fileData.append("file_path", file_path);
  fileData.append("type", "avatar");

  api
    .post("/images/upload", fileData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    })
    .then((res) => {
      api
        .post(`users/${user.id}/avatar`, { image_id: res.data.image.id })
        .then((res) => {
          userStore.setUser(res.data);
          $q.notify({
            message: "Avatar uploaded",
            color: "positive",
            icon: "cloud_done",
          });
        });
    });
};

const onRejected = () => {
  $q.notify({
    message: "File rejected. Format accepted : .jpg, .jpeg, .png",
    color: "negative",
    icon: "cloud_done",
  });
};

const $q = useQuasar();
const router = useRouter();
const userStore = useUserStore();

const user = reactive(userStore.user);

// const props = defineProps({
//   user: {
//     type: Object,
//     required: true,
//   },
// });

const form = reactive({
  name: user.name,
  email: user.email,
  old_password: "",
  password: "",
  password_confirmation: "",
});

const errors = ref(null);

const emits = defineEmits(["update:password"]);

const onSubmit = () => {
  const formFormatted = pickBy(form, (val) => val != "");

  api
    .put(`/users/${user.id}`, formFormatted)
    .then((response) => {
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
    })
    .catch((error) => {
      errors.value = error.response.data.errors;
    });
};

const password_delete = ref("");
const modalDelete = ref(false);
const deleteUser = () => {
  api
    .delete(`/users/${user.id}`, {
      data: {
        password_delete: password_delete.value,
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
