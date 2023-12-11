<script setup>
import { ref, reactive } from "vue";
import axios from "axios";
import { useToast } from "vue-toastification";
import { useRouter } from "vue-router";
import { useUserStore } from "../../stores/users.js";
import "vue-toastification/dist/index.css";

const router = useRouter();
const toast = useToast();
const userStore = useUserStore();

const credentials = reactive({
  password: "",
  pin: "",
});

const emit = defineEmits(["delete"]);

const deleteVCard = async () => {
  try {
    await userStore.deleteVcard(credentials);
    toast.success("VCard deleted successfully!");
    emit("deletedVCard");
    userStore.clearUser();
    router.push("/login"); // Redireciona para a página de login
  } catch (error) {
    console.log(error);
    if (error.response && error.response.status == 422) {
      errors.value = error.response.data.errors;
      toast.error("VCard has not been deleted due to validation errors!");
    } else {
      toast.error("VCard has not been deleted due to unknown server error!");
    }
  }
};
</script>

<template>
  <form
    class="row g-3 needs-validation"
    novalidate
    @submit.prevent="deleteVCard"
  >
    <h3 class="mt-5 mb-3">Delete VCard</h3>
    <hr />
    <div class="mb-3">
      <label for="inputPassword" class="form-label">Password</label>
      <input
        type="password"
        class="form-control"
        id="inputPassword"
        required
        v-model="credentials.password"
      />
    </div>
    <div class="mb-3">
      <label for="inputPin" class="form-label">Pin</label>
      <input
        type="password"
        class="form-control"
        id="inputPin"
        required
        v-model="credentials.pin"
      />
    </div>
    <div class="mb-3 d-flex justify-content-center">
      <button type="button" class="btn btn-danger px-5" @click="deleteVCard">
        Delete VCard
      </button>
    </div>
  </form>
</template>
