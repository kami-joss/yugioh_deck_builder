<template>
  <div>
    <q-form @submit="onSubmit" class="form-login q-mx-auto">
      <h2>Registration</h2>
      <q-input v-model="form.name" label="Username" filled />
      <q-input v-model="form.email" label="E-mail" filled />
      <image-uploader
        :hidden-btn="true"
        @added="onImageAdded"
        @removed="imageAdded = null"
      />
      <q-input
        v-model="form.password"
        label="Password"
        type="password"
        filled
      />
      <q-input
        v-model="form.password_confirmation"
        label="Password confirmation"
        type="password"
        filled
      />
      <div class="text-center">
        <q-btn label="Register" type="submit" color="primary" />
      </div>
    </q-form>
  </div>
</template>

<script setup>
import { api } from "boot/axios";
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import { useQuasar } from "quasar";
import { pickBy } from "lodash";

import ImageUploader from "../forms/ImageUploader.vue";
import { CLOSING } from "ws";

const router = useRouter();
const $q = useQuasar();
const form = reactive({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
  image_id: null,
});

const imageAdded = ref(null);

const onImageAdded = (image) => {
  imageAdded.value = image[0];
};

const uploadImage = async () => {
  const file_path = imageAdded.value;
  const fileData = new FormData();

  fileData.append("file_path", file_path);
  fileData.append("type", "avatar");

  return api.post("/images/upload", fileData, {
    headers: {
      "Content-Type": "multipart/form-data",
    },
  });
};

const register = async (data) => {
  const res = await api
    .post("/register", data)
    .then((res) => {
      $q.notify({
        message: "Subscription successful",
        color: "positive",
        position: "top",
        icon: "check",
      });
      router.replace("/login");
    })
    .catch((err) => {
      console.log(err);
      $q.notify({
        message: "Subscription failed",
        color: "negative",
        position: "top",
        icon: "cloud_done",
      });
    });

  return res;
};

const onSubmit = () => {
  let data = pickBy(form, (value) => value !== null && value !== "");
  if (imageAdded.value) {
    uploadImage().then((res) => {
      data.image_id = res.data.image?.id;
      register(data);
    });
  } else {
    register(data);
  }

  // router.replace("/login");

  // api.post("/register", data).then((res) => {
  //   $q.notify({
  //     message: "Subscription successful",
  //     color: "positive",
  //     position: "top",
  //     icon: "check",
  //   });
  //   router.replace("/login");
  // });
};
</script>
