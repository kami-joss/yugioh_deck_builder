<template>
  <q-uploader
    style="max-width: 300px"
    :label="label"
    :factory="upload"
    max-files="1"
    accept=".jpg, .jpeg, .png"
    :hide-upload-btn="hiddenBtn"
    @added="emits('added', $event)"
    @removed="emits('removed', $event)"
    @rejected="onRejected"
  />
</template>

<script setup>
import { api } from "boot/axios";
import { useQuasar } from "quasar";
import { defineProps, defineEmits } from "vue";

const props = defineProps({
  label: {
    type: String,
    default: "Pick an image",
  },
  hiddenBtn: {
    type: Boolean,
    default: false,
  },
});
const emits = defineEmits(["uploaded", "added"]);
const $q = useQuasar();

const upload = (files) => {
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
      emits("uploaded", res.data.image.id);
    });
};

const onRejected = () => {
  $q.notify({
    message: "File rejected. Format accepted : .jpg, .jpeg, .png",
    color: "negative",
    icon: "cloud_done",
  });
};
</script>
