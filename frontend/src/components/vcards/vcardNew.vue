<script setup>
import { ref } from "vue";
import axios from "axios";
import { useToast } from "vue-toastification";
import { useRouter } from "vue-router";
import "vue-toastification/dist/index.css";

const router = useRouter();
const toast = useToast();

const form = ref({
  name: "",
  phone: "",
  email: "",
  password: "",
  pin: "",
  photo: null,
});

const onFileChange = (e) => {
  try {
    const file = e.target.files[0];
    if (!file) {
      form.value.photo = null;
    } else {
      const reader = new FileReader();
      reader.onloadend = () => {
        form.value.photo = reader.result;
      };
      reader.readAsDataURL(file);
    }
  } catch (error) {
    form.value.photo = null;
  }
};

const emit = defineEmits(["save"]);

const submitForm = () => {
  const formData = new FormData();

  for (const key in form.value) {
    if (key === "photo" && form.value[key] === null) {
      continue;
    }
    formData.append(key, form.value[key]);
  }

  axios
    .post("/vcard/new", formData)
    .then((response) => {
      emit("save", response.data);
      router.push("/login");
      toast.success("vCard criado com sucesso!");
    })
    .catch((error) => {
      console.log(error.response);
      if (error.response && error.response.data) {
        if (
          error.response.data.message.includes("Integrity constraint violation")
        ) {
          toast.error("This phone number is already registered!");
        } else {
          toast.error(error.response.data.message);
        }
      } else {
        toast.error("Ocorreu um erro ao criar o vCard!");
      }
    });
};
</script>

<template>
  <form class="row g-3 needs-validation" novalidate @submit.prevent="submitForm">
    <h3 class="mt-5 mb-3">Create Vcard</h3>
    <hr />
    <div class="mb-3">
      <label for="inputName" class="form-label">Name</label>
      <input type="text" class="form-control" id="inputName" required v-model="form.name" />
    </div>
    <div class="mb-3">
      <label for="inputPhone" class="form-label">Phone Number</label>
      <input type="tel" class="form-control" id="inputPhone" required v-model="form.phone" pattern="[0-9]{9}" />
    </div>
    <div class="mb-3">
      <label for="inputEmail" class="form-label">Email</label>
      <input type="email" class="form-control" id="inputEmail" required v-model="form.email" />
    </div>
    <div class="mb-3">
      <label for="inputPassword" class="form-label">Password</label>
      <input type="password" class="form-control" id="inputPassword" required v-model="form.password" />
    </div>
    <div class="mb-3">
      <label for="inputPin" class="form-label">PIN</label>
      <input type="password" class="form-control" id="inputPin" required v-model="form.pin" />
    </div>
    <div class="mb-3">
      <label for="inputPhoto" class="form-label">Photo</label>
      <input type="file" class="form-control" id="inputPhoto" required @change="onFileChange" />
    </div>
    <div class="mb-3 d-flex justify-content-center">
      <button type="button" class="btn btn-success px-5" @click="submitForm">
        Register
      </button>
    </div>
  </form>
</template>
